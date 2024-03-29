<?php
namespace TYPO3\CMS\Core\Tests\Unit\Cache\Backend;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Cache\Exception\InvalidDataException;
use CAG\CagTests\Core\Unit\UnitTestCase;

/**
 * Testcase for the cache to redis backend
 *
 * This class has functional tests as well as implementation tests:
 * - The functional tests make API calls to the backend and check expected behaviour
 * - The implementation tests make additional calls with an own redis instance to
 * check stored data structures in the redis server, which can not be checked
 * by functional tests alone. Those tests will fail if any changes
 * to the internal data structure are done.
 *
 * Warning:
 * The unit tests use and flush redis database numbers 0 and 1 on the
 * redis host specified by environment variable typo3RedisHost
 */
class RedisBackendTest extends UnitTestCase
{
    /**
     * If set, the tearDown() method will flush the cache used by this unit test.
     *
     * @var \TYPO3\CMS\Core\Cache\Backend\RedisBackend
     */
    protected $backend = null;

    /**
     * Own redis instance used in implementation tests
     *
     * @var \Redis
     */
    protected $redis = null;

    /**
     * Set up this testcase
     */
    protected function setUp()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('redis extension was not available');
        }
        if (!getenv('typo3TestingRedisHost')) {
            $this->markTestSkipped('environment variable "typo3TestingRedisHost" must be set to run this test');
        }
        // Note we assume that if that typo3TestingRedisHost env is set, we can use that for testing,
        // there is no test to see if the daemon is actually up and running. Tests will fail if env
        // is set but daemon is down.
    }

    /**
     * Sets up the redis backend used for testing
     */
    protected function setUpBackend(array $backendOptions = [])
    {
        $mockCache = $this->createMock(\TYPO3\CMS\Core\Cache\Frontend\FrontendInterface::class);
        $mockCache->expects($this->any())->method('getIdentifier')->will($this->returnValue('TestCache'));
        // We know this env is set, otherwise setUp() would skip the tests
        $backendOptions['hostname'] = getenv('typo3TestingRedisHost');
        // If typo3TestingRedisPort env is set, use it, otherwise fall back to standard port
        $env = getenv('typo3TestingRedisPort');
        $backendOptions['port'] = is_string($env) ? (int)$env : 6379;
        $this->backend = new \TYPO3\CMS\Core\Cache\Backend\RedisBackend('Testing', $backendOptions);
        $this->backend->setCache($mockCache);
        $this->backend->initializeObject();
    }

    /**
     * Sets up an own redis instance for implementation tests
     */
    protected function setUpRedis()
    {
        // We know this env is set, otherwise setUp() would skip the tests
        $redisHost = getenv('typo3TestingRedisHost');
        // If typo3TestingRedisPort env is set, use it, otherwise fall back to standard port
        $env = getenv('typo3TestingRedisPort');
        $redisPort = is_string($env) ? (int)$env : 6379;

        $this->redis = new \Redis();
        $this->redis->connect($redisHost, $redisPort);
    }

    /**
     * Tear down this testcase
     */
    protected function tearDown()
    {
        if ($this->backend instanceof \TYPO3\CMS\Core\Cache\Backend\RedisBackend) {
            $this->backend->flush();
        }
        parent::tearDown();
    }

    /**
     * @test Functional
     */
    public function initializeObjectThrowsNoExceptionIfGivenDatabaseWasSuccessfullySelected()
    {
        try {
            $this->setUpBackend(['database' => 1]);
        } catch (Exception $e) {
            $this->assertTrue();
        }
    }

    /**
     * @test Functional
     */
    public function setDatabaseThrowsExceptionIfGivenDatabaseNumberIsNotAnInteger()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1279763057);

        $this->setUpBackend(['database' => 'foo']);
    }

    /**
     * @test Functional
     */
    public function setDatabaseThrowsExceptionIfGivenDatabaseNumberIsNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1279763534);

        $this->setUpBackend(['database' => -1]);
    }

    /**
     * @test Functional
     */
    public function setCompressionThrowsExceptionIfCompressionParameterIsNotOfTypeBoolean()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1289679153);

        $this->setUpBackend(['compression' => 'foo']);
    }

    /**
     * @test Functional
     */
    public function setCompressionLevelThrowsExceptionIfCompressionLevelIsNotInteger()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1289679154);

        $this->setUpBackend(['compressionLevel' => 'foo']);
    }

    /**
     * @test Functional
     */
    public function setCompressionLevelThrowsExceptionIfCompressionLevelIsNotBetweenMinusOneAndNine()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1289679155);

        $this->setUpBackend(['compressionLevel' => 11]);
    }

    /**
     * @test Functional
     */
    public function setConnectionTimeoutThrowsExceptionIfConnectionTimeoutIsNotInteger()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1487849315);

        $this->setUpBackend(['connectionTimeout' => 'foo']);
    }

    /**
     * @test Functional
     */
    public function setConnectionTimeoutThrowsExceptionIfConnectionTimeoutIsNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1487849326);

        $this->setUpBackend(['connectionTimeout' => -1]);
    }

    /**
     * @test Functional
     */
    public function setThrowsExceptionIfIdentifierIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1377006651);

        $this->setUpBackend();
        $this->backend->set([], 'data');
    }

    /**
     * @test Functional
     */
    public function setThrowsExceptionIfDataIsNotAString()
    {
        $this->expectException(InvalidDataException::class);
        $this->expectExceptionCode(1279469941);

        $this->setUpBackend();
        $this->backend->set($this->getUniqueId('identifier'), []);
    }

    /**
     * @test Functional
     */
    public function setThrowsExceptionIfLifetimeIsNegative()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1279487573);

        $this->setUpBackend();
        $this->backend->set($this->getUniqueId('identifier'), 'data', [], -42);
    }

    /**
     * @test Functional
     */
    public function setThrowsExceptionIfLifetimeIsNotNullOrAnInteger()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1279488008);

        $this->setUpBackend();
        $this->backend->set($this->getUniqueId('identifier'), 'data', [], []);
    }

    /**
     * @test Implementation
     */
    public function setStoresEntriesInSelectedDatabase()
    {
        $this->setUpRedis();
        $this->redis->select(1);
        $this->setUpBackend(['database' => 1]);
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $result = $this->redis->exists('identData:' . $identifier);
        if (is_int($result)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $result = (bool)$result;
        }
        $this->assertTrue($result);
    }

    /**
     * @test Implementation
     */
    public function setSavesStringDataTypeForIdentifierToDataEntry()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $this->assertSame(\Redis::REDIS_STRING, $this->redis->type('identData:' . $identifier));
    }

    /**
     * @test Implementation
     */
    public function setSavesEntryWithDefaultLifeTime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $defaultLifetime = 42;
        $this->backend->setDefaultLifetime($defaultLifetime);
        $this->backend->set($identifier, 'data');
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame($defaultLifetime, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Implementation
     */
    public function setSavesEntryWithSpecifiedLifeTime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $lifetime = 43;
        $this->backend->set($identifier, 'data', [], $lifetime);
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame($lifetime, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Implementation
     */
    public function setSavesEntryWithUnlimitedLifeTime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data', [], 0);
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame(31536000, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Functional
     */
    public function setOverwritesExistingEntryWithNewData()
    {
        $this->setUpBackend();
        $data = 'data 1';
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, $data);
        $otherData = 'data 2';
        $this->backend->set($identifier, $otherData);
        $fetchedData = $this->backend->get($identifier);
        $this->assertSame($otherData, $fetchedData);
    }

    /**
     * @test Implementation
     */
    public function setOverwritesExistingEntryWithSpecifiedLifetime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $data = 'data';
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, $data);
        $lifetime = 42;
        $this->backend->set($identifier, $data, [], $lifetime);
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame($lifetime, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Implementation
     */
    public function setOverwritesExistingEntryWithNewDefaultLifetime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $data = 'data';
        $identifier = $this->getUniqueId('identifier');
        $lifetime = 42;
        $this->backend->set($identifier, $data, [], $lifetime);
        $newDefaultLifetime = 43;
        $this->backend->setDefaultLifetime($newDefaultLifetime);
        $this->backend->set($identifier, $data, [], $newDefaultLifetime);
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame($newDefaultLifetime, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Implementation
     */
    public function setOverwritesExistingEntryWithNewUnlimitedLifetime()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $data = 'data';
        $identifier = $this->getUniqueId('identifier');
        $lifetime = 42;
        $this->backend->set($identifier, $data, [], $lifetime);
        $this->backend->set($identifier, $data, [], 0);
        $lifetimeRegisteredInBackend = $this->redis->ttl('identData:' . $identifier);
        $this->assertSame(31536000, $lifetimeRegisteredInBackend);
    }

    /**
     * @test Implementation
     */
    public function setSavesSetDataTypeForIdentifierToTagsSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data', ['tag']);
        $this->assertSame(\Redis::REDIS_SET, $this->redis->type('identTags:' . $identifier));
    }

    /**
     * @test Implementation
     */
    public function setSavesSpecifiedTagsInIdentifierToTagsSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tags = ['thatTag', 'thisTag'];
        $this->backend->set($identifier, 'data', $tags);
        $savedTags = $this->redis->sMembers('identTags:' . $identifier);
        sort($savedTags);
        $this->assertSame($tags, $savedTags);
    }

    /**
     * @test Implementation
     */
    public function setRemovesAllPreviouslySetTagsFromIdentifierToTagsSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tags = ['fooTag', 'barTag'];
        $this->backend->set($identifier, 'data', $tags);
        $this->backend->set($identifier, 'data', []);
        $this->assertSame([], $this->redis->sMembers('identTags:' . $identifier));
    }

    /**
     * @test Implementation
     */
    public function setRemovesMultiplePreviouslySetTagsFromIdentifierToTagsSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $firstTagSet = ['tag1', 'tag2', 'tag3', 'tag4'];
        $this->backend->set($identifier, 'data', $firstTagSet);
        $secondTagSet = ['tag1', 'tag3'];
        $this->backend->set($identifier, 'data', $secondTagSet);
        $actualTagSet = $this->redis->sMembers('identTags:' . $identifier);
        sort($actualTagSet);
        $this->assertSame($secondTagSet, $actualTagSet);
    }

    /**
     * @test Implementation
     */
    public function setSavesSetDataTypeForTagToIdentifiersSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'tag';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->assertSame(\Redis::REDIS_SET, $this->redis->type('tagIdents:' . $tag));
    }

    /**
     * @test Implementation
     */
    public function setSavesIdentifierInTagToIdentifiersSetOfSpecifiedTag()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'thisTag';
        $this->backend->set($identifier, 'data', [$tag]);
        $savedTagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        $this->assertSame([$identifier], $savedTagToIdentifiersMemberArray);
    }

    /**
     * @test Implementation
     */
    public function setAppendsSecondIdentifierInTagToIdentifiersEntry()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $firstIdentifier = $this->getUniqueId('identifier1-');
        $tag = 'thisTag';
        $this->backend->set($firstIdentifier, 'data', [$tag]);
        $secondIdentifier = $this->getUniqueId('identifier2-');
        $this->backend->set($secondIdentifier, 'data', [$tag]);
        $savedTagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        sort($savedTagToIdentifiersMemberArray);
        $identifierArray = [$firstIdentifier, $secondIdentifier];
        sort($identifierArray);
        $this->assertSame([$firstIdentifier, $secondIdentifier], $savedTagToIdentifiersMemberArray);
    }

    /**
     * @test Implementation
     */
    public function setRemovesIdentifierFromTagToIdentifiersEntryIfTagIsOmittedOnConsecutiveSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'thisTag';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->backend->set($identifier, 'data', []);
        $savedTagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        $this->assertSame([], $savedTagToIdentifiersMemberArray);
    }

    /**
     * @test Implementation
     */
    public function setAddsIdentifierInTagToIdentifiersEntryIfTagIsAddedOnConsecutiveSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $tag = 'thisTag';
        $this->backend->set($identifier, 'data', [$tag]);
        $savedTagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        $this->assertSame([$identifier], $savedTagToIdentifiersMemberArray);
    }

    /**
     * @test Implementation
     */
    public function setSavesCompressedDataWithEnabledCompression()
    {
        $this->setUpBackend([
            'compression' => true
        ]);
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $data = 'some data ' . microtime();
        $this->backend->set($identifier, $data);
        $uncompresedStoredData = '';
        try {
            $uncompresedStoredData = @gzuncompress($this->redis->get(('identData:' . $identifier)));
        } catch (\Exception $e) {
        }
        $this->assertEquals($data, $uncompresedStoredData, 'Original and compressed data don\'t match');
    }

    /**
     * @test Implementation
     */
    public function setSavesPlaintextDataWithEnabledCompressionAndCompressionLevel0()
    {
        $this->setUpBackend([
            'compression' => true,
            'compressionLevel' => 0
        ]);
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $data = 'some data ' . microtime();
        $this->backend->set($identifier, $data);
        $this->assertGreaterThan(0, substr_count($this->redis->get('identData:' . $identifier), $data), 'Plaintext data not found');
    }

    /**
     * @test Functional
     */
    public function hasThrowsExceptionIfIdentifierIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1377006653);

        $this->setUpBackend();
        $this->backend->has([]);
    }

    /**
     * @test Functional
     */
    public function hasReturnsFalseForNotExistingEntry()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->assertFalse($this->backend->has($identifier));
    }

    /**
     * @test Functional
     */
    public function hasReturnsTrueForPreviouslySetEntry()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $this->assertTrue($this->backend->has($identifier));
    }

    /**
     * @test Functional
     */
    public function getThrowsExceptionIfIdentifierIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        //@todo Add exception code with redis extension

        $this->setUpBackend();
        $this->backend->get([]);
    }

    /**
     * @test Functional
     */
    public function getReturnsPreviouslyCompressedSetEntry()
    {
        $this->setUpBackend([
            'compression' => true
        ]);
        $data = 'data';
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, $data);
        $fetchedData = $this->backend->get($identifier);
        $this->assertSame($data, $fetchedData);
    }

    /**
     * @test Functional
     */
    public function getReturnsPreviouslySetEntry()
    {
        $this->setUpBackend();
        $data = 'data';
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, $data);
        $fetchedData = $this->backend->get($identifier);
        $this->assertSame($data, $fetchedData);
    }

    /**
     * @test Functional
     */
    public function removeThrowsExceptionIfIdentifierIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1377006654);

        $this->setUpBackend();
        $this->backend->remove([]);
    }

    /**
     * @test Functional
     */
    public function removeReturnsFalseIfNoEntryWasDeleted()
    {
        $this->setUpBackend();
        $this->assertFalse($this->backend->remove($this->getUniqueId('identifier')));
    }

    /**
     * @test Functional
     */
    public function removeReturnsTrueIfAnEntryWasDeleted()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $this->assertTrue($this->backend->remove($identifier));
    }

    /**
     * @test Functional
     */
    public function removeDeletesEntryFromCache()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $this->backend->remove($identifier);
        $this->assertFalse($this->backend->has($identifier));
    }

    /**
     * @test Implementation
     */
    public function removeDeletesIdentifierToTagEntry()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'thisTag';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->backend->remove($identifier);
        $result = $this->redis->exists('identTags:' . $identifier);
        if (is_int($result)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $result = (bool)$result;
        }
        $this->assertFalse($result);
    }

    /**
     * @test Implementation
     */
    public function removeDeletesIdentifierFromTagToIdentifiersSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'thisTag';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->backend->remove($identifier);
        $tagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        $this->assertSame([], $tagToIdentifiersMemberArray);
    }

    /**
     * @test Implementation
     */
    public function removeDeletesIdentifierFromTagToIdentifiersSetWithMultipleEntries()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $firstIdentifier = $this->getUniqueId('identifier');
        $secondIdentifier = $this->getUniqueId('identifier');
        $tag = 'thisTag';
        $this->backend->set($firstIdentifier, 'data', [$tag]);
        $this->backend->set($secondIdentifier, 'data', [$tag]);
        $this->backend->remove($firstIdentifier);
        $tagToIdentifiersMemberArray = $this->redis->sMembers('tagIdents:' . $tag);
        $this->assertSame([$secondIdentifier], $tagToIdentifiersMemberArray);
    }

    /**
     * @test Functional
     */
    public function findIdentifiersByTagThrowsExceptionIfTagIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1377006655);

        $this->setUpBackend();
        $this->backend->findIdentifiersByTag([]);
    }

    /**
     * @test Functional
     */
    public function findIdentifiersByTagReturnsEmptyArrayForNotExistingTag()
    {
        $this->setUpBackend();
        $this->assertSame([], $this->backend->findIdentifiersByTag('thisTag'));
    }

    /**
     * @test Functional
     */
    public function findIdentifiersByTagReturnsAllIdentifiersTagedWithSpecifiedTag()
    {
        $this->setUpBackend();
        $firstIdentifier = $this->getUniqueId('identifier1-');
        $secondIdentifier = $this->getUniqueId('identifier2-');
        $thirdIdentifier = $this->getUniqueId('identifier3-');
        $tagsForFirstIdentifier = ['thisTag'];
        $tagsForSecondIdentifier = ['thatTag'];
        $tagsForThirdIdentifier = ['thisTag', 'thatTag'];
        $this->backend->set($firstIdentifier, 'data', $tagsForFirstIdentifier);
        $this->backend->set($secondIdentifier, 'data', $tagsForSecondIdentifier);
        $this->backend->set($thirdIdentifier, 'data', $tagsForThirdIdentifier);
        $expectedResult = [$firstIdentifier, $thirdIdentifier];
        $actualResult = $this->backend->findIdentifiersByTag('thisTag');
        sort($actualResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test Implementation
     */
    public function flushRemovesAllEntriesFromCache()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier, 'data');
        $this->backend->flush();
        $this->assertSame([], $this->redis->getKeys('*'));
    }

    /**
     * @test Functional
     */
    public function flushByTagThrowsExceptionIfTagIsNotAString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1377006656);

        $this->setUpBackend();
        $this->backend->flushByTag([]);
    }

    /**
     * @test Functional
     */
    public function flushByTagRemovesEntriesTaggedWithSpecifiedTag()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag1']);
        $this->backend->set($identifier . 'B', 'data', ['tag2']);
        $this->backend->set($identifier . 'C', 'data', ['tag1', 'tag2']);
        $this->backend->flushByTag('tag1');
        $expectedResult = [false, true, false];
        $actualResult = [
            $this->backend->has($identifier . 'A'),
            $this->backend->has($identifier . 'B'),
            $this->backend->has($identifier . 'C')
        ];
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test Functional
     */
    public function flushByTagsRemovesEntriesTaggedWithSpecifiedTags()
    {
        $this->setUpBackend();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag1']);
        $this->backend->set($identifier . 'B', 'data', ['tag2']);
        $this->backend->set($identifier . 'C', 'data', ['tag1', 'tag2']);
        $this->backend->set($identifier . 'D', 'data', ['tag3']);
        $this->backend->flushByTags(['tag1', 'tag2']);
        $expectedResult = [false, false, false, true];
        $actualResult = [
            $this->backend->has($identifier . 'A'),
            $this->backend->has($identifier . 'B'),
            $this->backend->has($identifier . 'C'),
            $this->backend->has($identifier . 'D')
        ];
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test Implementation
     */
    public function flushByTagRemovesTemporarySet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag1']);
        $this->backend->set($identifier . 'C', 'data', ['tag1', 'tag2']);
        $this->backend->flushByTag('tag1');
        $this->assertSame([], $this->redis->getKeys('temp*'));
    }

    /**
     * @test Implementation
     */
    public function flushByTagRemovesIdentifierToTagsSetOfEntryTaggedWithGivenTag()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'tag1';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->backend->flushByTag($tag);
        $result = $this->redis->exists('identTags:' . $identifier);
        if (is_int($result)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $result = (bool)$result;
        }
        $this->assertFalse($result);
    }

    /**
     * @test Implementation
     */
    public function flushByTagDoesNotRemoveIdentifierToTagsSetOfUnrelatedEntry()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifierToBeRemoved = $this->getUniqueId('identifier');
        $tagToRemove = 'tag1';
        $this->backend->set($identifierToBeRemoved, 'data', [$tagToRemove]);
        $identifierNotToBeRemoved = $this->getUniqueId('identifier');
        $tagNotToRemove = 'tag2';
        $this->backend->set($identifierNotToBeRemoved, 'data', [$tagNotToRemove]);
        $this->backend->flushByTag($tagToRemove);
        $this->assertSame([$tagNotToRemove], $this->redis->sMembers('identTags:' . $identifierNotToBeRemoved));
    }

    /**
     * @test Implementation
     */
    public function flushByTagRemovesTagToIdentifiersSetOfGivenTag()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $tag = 'tag1';
        $this->backend->set($identifier, 'data', [$tag]);
        $this->backend->flushByTag($tag);
        $result = $this->redis->exists('tagIdents:' . $tag);
        if (is_int($result)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $result = (bool)$result;
        }
        $this->assertFalse($result);
    }

    /**
     * @test Implementation
     */
    public function flushByTagRemovesIdentifiersTaggedWithGivenTagFromTagToIdentifiersSets()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag1', 'tag2']);
        $this->backend->set($identifier . 'B', 'data', ['tag1', 'tag2']);
        $this->backend->set($identifier . 'C', 'data', ['tag2']);
        $this->backend->flushByTag('tag1');
        $this->assertSame([$identifier . 'C'], $this->redis->sMembers('tagIdents:tag2'));
    }

    /**
     * @test Implementation
     */
    public function collectGarbageDoesNotRemoveNotExpiredIdentifierToDataEntry()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag']);
        $this->backend->set($identifier . 'B', 'data', ['tag']);
        $this->redis->delete('identData:' . $identifier . 'A');
        $this->backend->collectGarbage();
        $result = $this->redis->exists('identData:' . $identifier . 'B');
        if (is_int($result)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $result = (bool)$result;
        }
        $this->assertTrue($result);
    }

    /**
     * @test Implementation
     */
    public function collectGarbageRemovesLeftOverIdentifierToTagsSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag']);
        $this->backend->set($identifier . 'B', 'data', ['tag']);
        $this->redis->delete('identData:' . $identifier . 'A');
        $this->backend->collectGarbage();
        $expectedResult = [false, true];
        $resultA = $this->redis->exists('identTags:' . $identifier . 'A');
        $resultB = $this->redis->exists('identTags:' . $identifier . 'B');
        if (is_int($resultA)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $resultA = (bool)$resultA;
        }
        if (is_int($resultB)) {
            // Since 3.1.4 of phpredis/phpredis the return types has been changed
            $resultB = (bool)$resultB;
        }
        $actualResult = [
            $resultA,
            $resultB
        ];
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @test Implementation
     */
    public function collectGarbageRemovesExpiredIdentifierFromTagsToIdentifierSet()
    {
        $this->setUpBackend();
        $this->setUpRedis();
        $identifier = $this->getUniqueId('identifier');
        $this->backend->set($identifier . 'A', 'data', ['tag1', 'tag2']);
        $this->backend->set($identifier . 'B', 'data', ['tag2']);
        $this->redis->delete('identData:' . $identifier . 'A');
        $this->backend->collectGarbage();
        $expectedResult = [
            [],
            [$identifier . 'B']
        ];
        $actualResult = [
            $this->redis->sMembers('tagIdents:tag1'),
            $this->redis->sMembers('tagIdents:tag2')
        ];
        $this->assertSame($expectedResult, $actualResult);
    }
}
