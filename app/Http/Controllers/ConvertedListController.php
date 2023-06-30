<?php

namespace App\Http\Controllers;

use App\Models\ConvertedList;
use Illuminate\Http\Request;

class ConvertedListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $convertedlists = ConvertedList::orderBy('id', 'desc')->get();
        return view('home', compact('convertedlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_input' => 'required'
        ]);

        $input = $request->user_input;
        if (is_numeric($input)) {
            // Number to Word
            $converted = $this->numberToWords($input);
            $php_value = $input;
            
        } else {
            // Word to Number
            $converted = $this->wordsToNumber($input);
            $php_value = $converted;

        }

        // PHP TO USD conversion
        if (is_numeric($php_value)) {
            $usd_converted = $this->convertPHPtoUSD($php_value);
        } else {
            $usd_converted = "Error: Php Value is Not Numeric";
        }

        $conversion = new ConvertedList();
        $conversion->user_input = $request->user_input;
        $conversion->wn_conversion = $converted;
        $conversion->usd_conversion = $usd_converted;
        $conversion->save();

        return back();
    }

    public function wordsToNumber($words)
    {
        $numberWords = [
            'zero' => 0,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'eleven' => 11,
            'twelve' => 12,
            'thirteen' => 13,
            'fourteen' => 14,
            'fifteen' => 15,
            'sixteen' => 16,
            'seventeen' => 17,
            'eighteen' => 18,
            'nineteen' => 19,
            'twenty' => 20,
            'thirty' => 30,
            'forty' => 40,
            'fifty' => 50,
            'sixty' => 60,
            'seventy' => 70,
            'eighty' => 80,
            'ninety' => 90,
            'hundred' => 100,
            'thousand' => 1000,
            'million' => 1000000,
        ];
    
        $words = strtolower($words);
        $words = $this->correctSpacelessInput($words);
        $words = preg_replace('/ and /', ' ', $words);
        $words = preg_replace('/[^a-z ]/', '', $words);
        $words = explode(' ', $words);
        $total = 0;
        $currentValue = null;
    
        foreach ($words as $word) {
            if (isset($numberWords[$word])) {
                $value = $numberWords[$word];
    
                if (is_null($currentValue)) {
                    $currentValue = $value;
                } elseif ($value >= 1000) {
                    $total += $currentValue * $value;
                    $currentValue = null;
                } elseif ($value >= 100) {
                    $currentValue *= $value;
                } else {
                    $currentValue += $value;
                }
            } else {
                // Error in conversion
                return "Error: Invalid word detected!";
            }
        }
    
        return $total + $currentValue;
    }

    public function numberToWords($number)
    {
        $ones = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine'
        );

        $tens = array(
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety'
        );

        $number = (int)$number;

        if ($number < 0) {
            return 'minus ' . $this->numberToWords(abs($number));
        }

        $words = '';

        if (($number / 1000000) >= 1) {
            $millions = (int)($number / 1000000);
            $words .= $this->numberToWords($millions) . ' million ';
            $number %= 1000000;
        }

        if (($number / 1000) >= 1) {
            $thousands = (int)($number / 1000);
            $words .= $this->numberToWords($thousands) . ' thousand ';
            $number %= 1000;
        }

        if (($number / 100) >= 1) {
            $hundreds = (int)($number / 100);
            $words .= $this->numberToWords($hundreds) . ' hundred ';
            $number %= 100;
        }

        if ($number > 0) {
            if ($words != '') {
                $words .= 'and ';
            }

            if ($number < 10) {
                $words .= $ones[$number];
            } elseif ($number < 20) {
                $words .= $tens[$number];
            } else {
                $words .= $tens[floor($number / 10) * 10];
                $number %= 10;

                if ($number > 0) {
                    $words .= ' ' . $ones[$number];
                }
            }
        }

        return $words;
    }

    public function convertPHPtoUSD($phpAmount)
    {
        // ExchangeRate-API
        // APIKey = 0d8827dc5470e011550e2fb8
        $req_url = 'https://v6.exchangerate-api.com/v6/0d8827dc5470e011550e2fb8/latest/PHP';

        // Use cURL to make the API request
        $ch = curl_init($req_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_json = curl_exec($ch);
        curl_close($ch);

        // Check if the API request was successful
        if ($response_json === false) {
            return "Error: Failed to retrieve exchange rate data.";
        }

        // Try/catch for json_decode operation
        try {
            // Decoding
            $response = json_decode($response_json);

            // Check if JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return "Error: Failed to parse response from the exchange rate API.";
            }

            // Check for success response from the API
            if ($response->result === 'success') {
                $base_price = $phpAmount;
                $USD_PRICE = $base_price * $response->conversion_rates->USD;
                return $USD_PRICE;
            } else {
                return "Error: Exchange rate API did not return a success response.";
            }
        } catch (Exception $e) {
            return "Error: An exception occurred during USD conversion.";
        }
    }

    public function correctSpacelessInput($input) {
        // Define a dictionary of number words and their corresponding values
        $numberWords = [
            'zero' => 0,
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
            'ten' => 10,
            'eleven' => 11,
            'twelve' => 12,
            'thirteen' => 13,
            'fourteen' => 14,
            'fifteen' => 15,
            'sixteen' => 16,
            'seventeen' => 17,
            'eighteen' => 18,
            'nineteen' => 19,
            'twenty' => 20,
            'thirty' => 30,
            'forty' => 40,
            'fifty' => 50,
            'sixty' => 60,
            'seventy' => 70,
            'eighty' => 80,
            'ninety' => 90,
            'hundred' => 100,
            'thousand' => 1000,
            'million' => 1000000,
        ];
    
        // Iterate through the number words dictionary
        foreach ($numberWords as $word => $value) {
            // Replace the number word with a space before it
            $input = str_replace($word, ' ' . $word, $input);
        }
    
        // Trim any leading or trailing spaces
        $input = trim($input);
        $input = preg_replace('/\s+/', ' ', $input);
    
        return $input;
    }
}