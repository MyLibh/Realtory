<?php

/**
 * This file contents components for site parsing.
 *
 * Parser description.
 *
 * @version 0.3
 * @author Aleksei Goliguzov & Алексей Кристев
 */

namespace Parser;

//===================================================================================================================
//=====================================================CONSTANTS=====================================================
//===================================================================================================================

const TOKENS = ['Link',
				'Block',
		        'Area_total',
				'Area_kitchen',
				'Area_living',
				'Floor',
				'Price',
				'Type',
				'Rooms',
				'Underground',
				'Underground_distance'];

//===================================================================================================================
//======================================================CLASSES======================================================
//===================================================================================================================

final class SiteElement
{
	public $str,
		   $index; // The index of the desired element in the parsed line

	public function __construct($str, $index = 1)
	{
		$this->str   = $str;
		$this->index = $index;
	}
}

final class SiteParser
{
	private $elements = [];

	public function __construct(string $file)
	{
		$data        = file('data\ParsingInfo\\' . $file);
		$file_length = count($data);
		for($i = 1; $i < $file_length; $i += 4)
		{
			assert($i >= 0 && $i < $file_length && $i + 1 < $file_length && $i + 2 < $file_length);

			$data[$i] = trim($data[$i]);
			if(!strlen($data[$i]))
			{
				$i -= 2;

				continue;
			}
			else if(!IsToken($data[$i]))
			{
				echo '\'' . $data[$i] . '\' token does not exist<br/>';

				continue;
			}

			if(!strlen(trim($data[$i + 2])))
			{
				$this->elements[$data[$i]] = new SiteElement(trim($data[$i + 1]));

				$i--;
			}
			else
				$this->elements[$data[$i]] = new SiteElement(trim($data[$i + 1]), (int) trim($data[$i + 2]));
		}
	}

	public function parseToken(string $token) : array
	{
		$data = file_get_contents($this->elements['Link']->str);

		preg_match_all($this->elements[$token]->str, $data, $parsed_info);

		$ret_val = [];
		$i       = 0;
		foreach($parsed_info[$this->elements[$token]->index] as $item)
			$ret_val[$i++] = $item;

		return $ret_val;
	}

	public function parseAll() : array
	{
		$data = file_get_contents($this->elements['Link']->str);

		$ret_val = [];
		$i       = 0;
		$keys    = array_keys($this->elements);
		foreach($this->elements as $element)
		{
			preg_match_all($element->str, $data, $parsed_info);

			$j = 0;
			foreach($parsed_info[$element->index] as $item)
				$ret_val[$keys[$i]][$j++] = $item;

			$i++;
		}

		return $ret_val;
	}

	public function parseAsAds() : array
	{
		$data = file_get_contents($this->elements['Link']->str);

		$ret_val = [];
		$i       = 0;
		$keys    = array_keys($this->elements);
		foreach($this->elements as $element)
		{
			preg_match_all($element->str, $data, $parsed_info);

			$j = 0;
			foreach($parsed_info[$element->index] as $item)
				$ret_val[$j++][$keys[$i]] = $item;

			$i++;
		}

		return $ret_val;
	}

	public function parseAds() : array
	{
		if(!key_exists('Block', $this->elements))
			return [];

		$data = file_get_contents($this->elements['Link']->str);

		$offers       = preg_split($this->elements['Block']->str, $data);
		$offers_index = $this->elements['Block']->index;
		$offers_num   = count($offers);

		$ret_val = [];
		for($i = $offers_index; $i < $offers_num; $i++)
			foreach(TOKENS as $token)
			{
				if($token === 'Link' || $token === 'Block')
					continue;

				preg_match($this->elements[$token]->str, $offers[$i], $parsed_info);
				if(isset($parsed_info[$this->elements[$token]->index]))
					$ret_val[$i - $offers_index][$token] = $parsed_info[$this->elements[$token]->index];
				else
					$ret_val[$i - $offers_index][$token] = 'not found';
			}
		var_dump($ret_val);

		return $ret_val;
	}
}


//===================================================================================================================
//=====================================================FUNCTIONS=====================================================
//===================================================================================================================

function IsToken($smth) : bool
{
	foreach(TOKENS as $token)
		if(strcmp($smth, $token) === 0)
			return (true);

	return (false);
}

?> 