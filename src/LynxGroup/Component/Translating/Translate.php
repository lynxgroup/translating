<?php namespace LynxGroup\Component\Translating;

class Translate extends \Odm\Document
{
	public function setKey($key)
	{
		$this->data['key'] = $key;

		return $this->setDirty();
	}

	public function getUsername()
	{
		return isset($this->data['key']) ? $this->data['key'] : null;
	}

	public function setContents(array $contents)
	{
		$this->data['content'] = $contents;

		return $this->setDirty();
	}

	public function setContent($locale, $content)
	{
		$this->data['content'][$locale] = $content;

		return $this->setDirty();
	}

	public function getContent($locale)
	{
		return isset($this->data['content'][$locale]) ? $this->data['content'][$locale] : '';
	}
}

