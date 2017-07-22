<?php
/**
 * Unit test for the plugin
 */
declare(strict_types=1);

namespace Tests;

use App\Plugin;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginTest
 * @package Tests
 */
class PluginTest extends TestCase
{
    /**
     * @var Plugin
     */
    protected $plugin;
    
    public function setUp()
    {
        $this->plugin = new Plugin();
    }

    public function testOnContentPrepare_osapply_NoChange()
    {
        $context = $params = null;
        $article = (object) ['text' => 'content here with no replacements', 'title' => 'the title'];
        
        $this->plugin->onContentPrepare($context, $article, $params);
        $this->assertSame('content here with no replacements', $article->text);
    }
}