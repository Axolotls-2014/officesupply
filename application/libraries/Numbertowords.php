<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Numbertowords {

	function convert_number($number) {
		// if (($number < 0) || ($number > 999999999)) {
		// 	throw new Exception("Number is out of range");
		// }

		// $Gn = floor($number / 1000000);
		// /* Millions (giga) */
		// $number -= $Gn * 1000000;
		// $kn = floor($number / 1000);
		// /* Thousands (kilo) */
		// $number -= $kn * 1000;
		// $Hn = floor($number / 100);
		// /* Hundreds (hecto) */
		// $number -= $Hn * 100;
		// $Dn = floor($number / 10);
		// /* Tens (deca) */
		// $n = $number % 10;
		// /* Ones */

		// $res = "";

		// if ($Gn) {
		// 	$res .= $this->convert_number($Gn) .  "Million";
		// }

		// if ($kn) {
		// 	$res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
		// }

		// if ($Hn) {
		// 	$res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
		// }

		// $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		// $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

		// if ($Dn || $n) {
		// 	if (!empty($res)) {
		// 		$res .= " and ";
		// 	}

		// 	if ($Dn < 2) {
		// 		$res .= $ones[$Dn * 10 + $n];
		// 	} else {
		// 		$res .= $tens[$Dn];

		// 		if ($n) {
		// 			$res .= "-" . $ones[$n];
		// 		}
		// 	}
		// }

		// if (empty($res)) {
		// 	$res = "zero";
		// }

		// return $res;

		// Indian Version

		$hyphen      = ' ';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            100000             => 'lakh',
            10000000          => 'crore'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number($remainder);
                }
                break;
            case $number < 100000:
                $thousands   = ((int) ($number / 1000));
                $remainder = $number % 1000;

                $thousands = $this->convert_number($thousands);

                $string .= $thousands . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number($remainder);
                }
                break;
            case $number < 10000000:
                $lakhs   = ((int) ($number / 100000));
                $remainder = $number % 100000;

                $lakhs = $this->convert_number($lakhs);

                $string = $lakhs . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number($remainder);
                }
                break;
            case $number < 1000000000:
                $crores   = ((int) ($number / 10000000));
                $remainder = $number % 10000000;

                $crores = $this->convert_number($crores);

                $string = $crores . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= $separator . $this->convert_number($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
	}

    function number_format_i($amount)
    {
        $amount = round($amount,2);

        $amountArray =  explode('.', $amount);
        if(count($amountArray)==1)
        {
            $int = $amountArray[0];
            $des=00;
        }
        else {
            $int = $amountArray[0];
            $des=$amountArray[1];
        }
        if(strlen($des)==1)
        {
            $des=$des."0";
        }
        if($int>=0)
        {
            $int = $this->numFormatIndia( $int );
            $themoney = $int.".".$des;
        }

        else
        {
            $int=abs($int);
            $int = $this->numFormatIndia( $int );
            $themoney= "-".$int.".".$des;
        }   
        return $themoney;
    }

    function numFormatIndia($num)
    {

        $explrestunits = "";
        if(strlen($num)>3)
        {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.
    }

}
?>
