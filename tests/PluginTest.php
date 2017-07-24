<?php
/**
 * Unit test for the plugin
 */
declare(strict_types=1);

namespace Tests;

use App\ParamsContract;
use App\Plugin;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginTest
 * @package Tests
 */
class PluginTest extends TestCase
{
    /**
     * @dataProvider onContentPrepareProvider
     * @param $osApplyValue
     * @param $pkApplyValue
     * @param $article
     * @param $expectedText
     */
    public function testOnContentPrepare($osApplyValue, $pkApplyValue, $article, $expectedText)
    {
        $plugin = new Plugin(new ParamsMock($osApplyValue, $pkApplyValue));
        $context = $params = null;
        $plugin->onContentPrepare($context, $article, $params);
        $this->assertSame($expectedText, $article->text);
    }

    /**
     * @return array
     */
    public function onContentPrepareProvider()
    {
        return [
            ['ignore', 'ignore', (object) ['text' => 'content here with no replacements', 'title' => 'the title'], 'content here with no replacements'],
            ['some value {title} end value', 'ignore', (object) ['text' => 'content here {title} more content', 'title' => 'the title here'], 'content here {title} more content'],
            
            ['some value', 'ignore', (object) ['text' => 'content here {osapply} more content', 'title' => 'the title'], 'content here some value more content'],
            ['some value {title} end value', 'ignore', (object) ['text' => 'content here {osapply} more content', 'title' => 'title'], 'content here some value title end value more content'],
            ['some value {title} end value', 'ignore', (object) ['text' => 'content here {osapply} more content', 'title' => 'the title here'], 'content here some value the_title_here end value more content'],

            ['ignore', 'some value', (object) ['text' => 'content here {pkapply} more content', 'title' => 'the title'], 'content here some value more content'],
            ['ignore', 'some value {title} end value', (object) ['text' => 'content here {pkapply} more content', 'title' => 'title'], 'content here some value dGl0bGU= end value more content'],

            [
                'os_apply_value {title} and after', 
                'pk_apply_value {title} end value', 
                (object) ['text' => 'content here {osapply} more content {pkapply} end content', 
                'title' => 'title here'], 
                'content here os_apply_value title_here and after more content pk_apply_value dGl0bGUgaGVyZQ== end value end content'
            ],
        ];
    }
}

/**
 * Class ParamsMock
 *
 * Just a simple shim for our plugin to provide context-aware values
 *
 * @package App
 */
class ParamsMock implements ParamsContract
{
    /**
     * @var string {osapply} value
     */
    protected $osApplyValue;

    /**
     * @var string {pkapply} value
     */
    protected $pkApplyValue;

    /**
     * ParamsMock constructor.
     * @param $osApplyValue
     * @param $pkApplyValue
     */
    public function __construct(string $osApplyValue, string $pkApplyValue)
    {
        $this->osApplyValue = $osApplyValue;
        $this->pkApplyValue = $pkApplyValue;
    }

    /**
     * Get which one of the placeholders
     *
     * @param string $incoming
     * @return string
     */
    public function get(string $incoming):string {
        return $incoming == 'osapply' ? $this->osApplyValue : $this->pkApplyValue;
    }
}