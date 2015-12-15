<?php namespace LynxGroup\Component\Translating;

use LynxGroup\Contracts\Translating\Translator as TranslatorInterface;

use LynxGroup\Contracts\Odm\Document;

class Translator implements TranslatorInterface
{
	protected $repository;

	protected $fallback;

	protected $locale;

	public function __construct(TranslateRepository $repository, $fallback, $locale)
	{
		$this->repository = $repository;

		$this->fallback = $fallback;

		$this->locale = $locale;
	}

	public function trans($key, array $args = [])
	{
		$trans = $this->repository->query()->where('key', $key)->find();

		if( $trans )
		{
			if( $content = $trans->getContent($this->locale) )
			{
				return $this->applyArgs($content, $args);
			}

			if( $content = $trans->getContent($this->fallback) )
			{
				return $this->applyArgs($content, $args);
			}
		}

		return "_{$this->applyArgs($key, $args)}_";
	}

	public function applyArgs($content, $args)
	{
		foreach( $args as $search => $replace )
		{
			$content = str_replace($search, $replace, $content);
		}

		return $content;
	}

	public function transDoc(Document $doc, $field, array $args = [])
	{
		if( $content = $doc->{'get'.ucfirst($field)}($this->locale) )
		{
			return $this->applyArgs($content, $args);
		}

		if( $content = $doc->{'get'.ucfirst($field)}($this->fallback) )
		{
			return $this->applyArgs($content, $args);
		}

		return "_".str_replace('\\', '-', strtolower(get_class($doc)))."_{$field}_";
	}
}
