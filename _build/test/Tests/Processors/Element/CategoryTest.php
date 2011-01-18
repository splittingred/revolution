<?php
/**
 * MODx Revolution
 *
 * Copyright 2006-2010 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
/**
 * Tests related to element/category/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Element
 * @group Category
 * @group CategoryProcessors
 */
class CategoryProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'element/category/';

    /**
     * Setup some basic data for this test.
     */
    public static function setUpBeforeClass() {
        $modx = MODxTestHarness::_getConnection();
        $modx->error->reset();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory'));
        if ($category) $category->remove();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory2'));
        if ($category) $category->remove();
    }

    /**
     * Cleanup data after this test.
     */
    public static function tearDownAfterClass() {
        $modx = MODxTestHarness::_getConnection();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory'));
        if ($category) $category->remove();
        $category = $modx->getObject('modCategory',array('category' => 'UnitTestCategory2'));
        if ($category) $category->remove();
    }

    /**
     * Tests the element/category/create processor, which creates a Category
     * @dataProvider providerCategoryCreate
     */
    public function testCategoryCreate($shouldPass,$categoryPk) {
        if (empty($categoryPk)) return false;
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',array(
            'category' => $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modCategory',$categoryPk);
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not create Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     */
    public function providerCategoryCreate() {
        return array(
            array(true,'UnitTestCategory'),
            array(true,'UnitTestCategory2'),
            array(false,'UnitTestCategory2'),
        );
    }

    /**
     * Tests the element/category/get processor, which gets a Category
     * @dataProvider providerCategoryGet
     */
    public function testCategoryGet($shouldPass,$categoryPk) {
        if (empty($categoryPk)) return false;

        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     */
    public function providerCategoryGet() {
        return array(
            array(true,'UnitTestCategory'),
            array(false,234),
        );
    }

    /**
     * Tests the element/category/remove processor, which removes a Category
     * @dataProvider providerCategoryRemove
     */
    public function testCategoryRemove($shouldPass,$categoryPk) {
        if (empty($categoryPk)) return false;

        $category = $this->modx->getObject('modCategory',array('category' => $categoryPk));
        if (empty($category) && $shouldPass) {
            $this->fail('No category found "'.$categoryPk.'" as specified in test provider.');
            return false;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $category ? $category->get('id') : $categoryPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Category: `'.$categoryPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/category/create processor test.
     */
    public function providerCategoryRemove() {
        return array(
            array(true,'UnitTestCategory'),
            array(false,234),
        );
    }
}