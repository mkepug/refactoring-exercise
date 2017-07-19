<?php
public function onContentPrepare($context, &$article, &$params, $page = 0) {
  if ($this->_shouldNotTry($context, $article)){ return; }
  $article->text = str_replace('{osapply}', $this->_oldButtonText($article->title), $article->text); $article->text = str_replace('{pkapply}', $this->_newButtonText($article->title), $article->text);
}

private function _oldButtonText($title) {
  $oldTitle = $this->_formatOldTitle($title); $baseText = $this->params->get('osapply'); return str_replace('{title}', $oldTitle, $baseText);
}

private function _formatOldTitle($title) {
  return preg_replace("/\s+/", "_", $title);
}

private function _newButtonText($title) {
  $newTitle = $this->_formatNewTitle($title); $baseText = $this->params->get('pkapply'); return str_replace('{title}', $newTitle, $baseText);
}

private function _formatNewTitle($title) {
  return base64_encode($title); 
}
