<?php

namespace MolnApps\Database\Adapter\MemoryAdapterHelpers;

class Where
{
	private $where = [];

	public function __construct(array $where)
	{
		$this->where = $this->normalize($where);
	}

	public static function create(array $where)
	{
		return new static($where);
	}

	public function toArray()
	{
		return $this->where;
	}

	private function normalize(array $where)
	{
		$result = [];

		foreach ($where as $key => $value) {
			if ($this->isKeyValuePair($key, $value)) {
				$value = [$key, 'eq', $value];
			} 

			$result[] = $value;
		}

		return $result;
	}

	private function isKeyValuePair($key, $value)
	{
		return ! is_numeric($key);
	}
}