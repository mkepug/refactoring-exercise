<?php
/**
 * Demonstration file for refactoring of the MKEPUG Plugin demo
 */
declare(strict_types=1);

namespace App;

/**
 * Class Plugin
 * @package App
 */
class Plugin
{
    /**
     * The replacement placeholders and what version of title filtering they use
     */
    const PLACEHOLDER_TITLE_FILTER_VERSION = [
        'osapply' => 1,
        'pkapply' => 2
    ];
    
    /**
     * @var ParamsContract
     */
    protected $params;

    /**
     * Plugin constructor.
     * @param $params
     */
    public function __construct(ParamsContract $params)
    {
        $this->params = $params;
    }

    /**
     * Handler for content prepare:
     * 
     * - replaces {osapply} and {pkapply} template items
     * 
     * @param $context mixed passed to should not try only
     * @param $article mixed an object that contains values that are filtered
     * @param $params mixed ignored
     * @param int $page ignored
     */
    public function onContentPrepare($context, $article, &$params, $page = 0) {
        if (!$this->shouldNotTry($context, $article)) {
            foreach (array_keys(static::PLACEHOLDER_TITLE_FILTER_VERSION) as $placeholder) {
                $this->replacePlaceholder($placeholder, $article);
            }
        }
    }

    /**
     * Takes a placeholder and an article, and replaces it using the proper strategy for filtering
     * 
     * @param $placeholder
     * @param $article
     */
    protected function replacePlaceholder($placeholder, $article)
    {
        $titleFilterFunction = sprintf('version%dFilter', static::PLACEHOLDER_TITLE_FILTER_VERSION[$placeholder]);
        
        $replacementText = str_replace(
            '{title}', 
            $this->$titleFilterFunction($article->title), 
            $this->params->get($placeholder)
        );
        
        $article->text = str_replace('{' . $placeholder . '}', $replacementText, $article->text);
    }

    /**
     * "old" filtering version
     * 
     * @param string $incoming
     * @return mixed
     */
    protected function version1Filter(string $incoming)
    {
        return preg_replace('/\s+/', '_', $incoming);
    }

    /**
     * "new" filtering version
     * 
     * @param string $incoming
     * @return string
     */
    protected function version2Filter(string $incoming)
    {
        return base64_encode($incoming);
    }

    /**
     * Whether we should try this or not
     * 
     * - this is a stub
     * 
     * @param $context
     * @param $article
     * @return bool
     */
    protected function shouldNotTry($context, $article)
    {
        return false;
    }
}