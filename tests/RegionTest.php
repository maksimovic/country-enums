<?php

declare(strict_types=1);

namespace Tests;

use CountryEnums\Country;
use CountryEnums\Exceptions\EnumNotFoundException;
use CountryEnums\Region;
use PHPUnit\Framework\TestCase;

class RegionTest extends TestCase {
	public function testRetrievesRegionByCode()
	{
		$region = Region::from('US_CA');

		$this->assertInstanceOf(Region::class, $region);
		$this->assertEquals('US_CA', $region->value);
	}

	public function testThrowsExceptionForInvalidRegionCode()
	{
		$this->expectException(EnumNotFoundException::class);

		Region::fromCode('INVALID_CODE');
	}

	public function testRegionFromCode()
	{
		$region = Region::fromCode('united_states_california');

		$this->assertInstanceOf(Region::class, $region);
		$this->assertEquals('US_CA', $region->value);

		$noRegionFromCode = Region::tryFromCode('invalid_code');
		$this->assertNull($noRegionFromCode);
	}

	public function testRetrievesCountryForRegion()
	{
		$region = Region::from('US_CA');
		$country = $region->country();

		$this->assertInstanceOf(Country::class, $country);
		$this->assertEquals('US', $country->value);
	}

	public function testConvertsRegionToArray()
	{
		$region = Region::from('US_CA');
		$array = $region->toArray();

		$this->assertArrayHasKey('label', $array);
		$this->assertArrayHasKey('value', $array);
		$this->assertArrayHasKey('country', $array);
		$this->assertEquals('US_CA', $array['value']);
	}

	public function testRetrievesRandomRegion()
	{
		$region = Region::random();

		$this->assertInstanceOf(Region::class, $region);
		$this->assertContains($region->value, Region::getValues());
	}

	public function testGetValuesForCountry()
	{
		$values = Region::getValues('US');

		$this->assertIsArray($values);
		$this->assertNotEmpty($values);
		$this->assertContains(Region::US_CA->value, $values);
		$this->assertContains(Region::US_TX->value, $values);
	}

	public function testRetrievesRegionLabel()
	{
		$region = Region::from('US_CA');
		$label = $region->label();

		$this->assertEquals('California', $label);
	}

	public function testRetrievesRegionCode()
	{
		$region = Region::from('US_CA');
		$code = $region->code();

		$this->assertEquals('united_states_california', $code);
	}

	public function testRetrievesOptionsForCountry()
	{
		$options = Region::getOptions('US');

		$this->assertIsArray($options);
		$this->assertNotEmpty($options);
		$this->assertArrayHasKey('US_AL', $options);
		$this->assertEquals('Alabama', $options['US_AL']);
	}

	public function testAllOptions()
	{
		$options = Region::getOptions();

		$this->assertIsArray($options);
		$this->assertNotEmpty($options);
	}

	public function testParsesRegionSuccessfully()
	{
		$region = Region::parse('US_CA');

		$this->assertInstanceOf(Region::class, $region);
		$this->assertEquals('US_CA', $region->value);
	}

	public function testReturnsNullForInvalidRegionParse()
	{
		$region = Region::tryParse('INVALID_CODE');

		$this->assertNull($region);
	}

	public function testParseThrowsException()
	{
		$this->expectException(EnumNotFoundException::class);

		Region::parse(null);
	}

	public function testUselessParse()
	{
		$region = Region::parse(Region::AM_AG);
		$this->assertInstanceOf(Region::class, $region);
	}

	public function testToJson()
	{
		$region = Region::from('US_CA');
		$json = $region->toJson();

		$this->assertIsString($json);
		$this->assertStringContainsString('"value":"US_CA"', $json);
		$this->assertStringContainsString('"label":"California"', $json);
	}

	public function testFor()
	{
		$regions = Region::for(Country::US);

		$this->assertIsArray($regions);
		$this->assertNotEmpty($regions);

		$noRegions = Region::for(Country::AD);
		$this->assertIsArray($noRegions);
		$this->assertCount(0, $noRegions);

	}

}
