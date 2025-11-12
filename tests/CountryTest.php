<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use CountryEnums\Country;
use CountryEnums\Exceptions\EnumNotFoundException;

class CountryTest extends TestCase
{
	public function testRetrieveAllCountryValues()
	{
		$values = Country::getValues();

		$this->assertNotEmpty($values);
		$this->assertContains('US', $values);
		$this->assertContains('GB', $values);
	}

	public function testRetrieveCountryByCode()
	{
		$country = Country::fromCode('united_states');

		$this->assertInstanceOf(Country::class, $country);
		$this->assertEquals('US', $country->name);

		$tryCountry = Country::tryFromCode('united_states');
		$this->assertInstanceOf(Country::class, $tryCountry);
		$this->assertEquals('US', $tryCountry->name);

		$countryThatDoesntExist = Country::tryFromCode('non_existent_country');
		$this->assertNull($countryThatDoesntExist);
	}

	public function testThrowsExceptionForInvalidCountryCode()
	{
		$this->expectException(EnumNotFoundException::class);

		Country::fromCode('INVALID');
	}

	public function testRetrieveRandomCountry()
	{
		$country = Country::random();

		$this->assertInstanceOf(Country::class, $country);
		$this->assertContains($country->value, Country::getValues());
	}

	public function testRetrieveRegionsForCountry()
	{
		$country = Country::from('US');
		$regions = $country->regions();

		$this->assertIsArray($regions);
	}

	public function testConvertCountryToArray()
	{
		$country = Country::from('US');
		$array = $country->toArray();

		$this->assertArrayHasKey('label', $array);
		$this->assertArrayHasKey('value', $array);
		$this->assertArrayHasKey('regions', $array);
		$this->assertArrayHasKey('code', $array);
	}

	public function testRetrieveSvgFlagPath()
	{
		$country = Country::from('US');
		$svgPath = $country->svgFlag();

		$this->assertIsString($svgPath);
		$this->assertFileExists($svgPath);
	}

	public function testRetrieveCountryLabel()
	{
		$country = Country::from('US');
		$label = $country->label();

		$this->assertEquals('United States', $label);
	}

	public function testRetrieveCountryDemonym()
	{
		$country = Country::from('US');
		$demonym = $country->demonym();

		$this->assertEquals('American', $demonym);
	}

	public function testGetRegions()
	{
		$country = Country::from('US');
		$regions = $country->getRegionValues();

		$this->assertIsArray($regions);
		$this->assertNotEmpty($regions);
	}

	public function testGetOptions()
	{
		$options = Country::getOptions();

		$this->assertIsArray($options);
		$this->assertNotEmpty($options);
	}

	public function testGetFlagContents()
	{
		$country = Country::from('US');

		$svgFlagContents = $country->svgFlagContents();

		$this->assertIsString($svgFlagContents);
		$this->assertStringContainsString('<svg', $svgFlagContents);

		$pngFlagContents = $country->pngFlagContents();
		$this->assertIsString($pngFlagContents);
		$this->assertStringStartsWith("\x89PNG", $pngFlagContents);

		$pngFlagContents300px = $country->pngFlagContents(300);
		$this->assertIsString($pngFlagContents300px);
		$this->assertStringStartsWith("\x89PNG", $pngFlagContents300px);

		$pngFlagContents150px = $country->pngFlagContents(150);
		$this->assertIsString($pngFlagContents150px);
		$this->assertStringStartsWith("\x89PNG", $pngFlagContents150px);
	}

	public function testTryParse()
	{
		$country = Country::tryParse('US');
		$this->assertEquals('US', $country->name);

		$countryNull = Country::tryParse('Non Existent Country');
		$this->assertNull($countryNull);
	}

	public function testUselessParse()
	{
		$country = Country::parse(Country::AD);
		$this->assertEquals('AD', $country->name);
	}

	public function testParsingNull()
	{
		$this->expectException(EnumNotFoundException::class);
		Country::parse(null);
	}

	public function testToJson()
	{
		$country = Country::from('US');
		$json = $country->toJson();

		$this->assertJson($json);
		$this->assertStringContainsString('"label":"United States"', $json);
	}

}
