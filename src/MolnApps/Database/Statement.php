<?php

namespace MolnApps\Database;

interface Statement
{
	public function getStatement();
	public function getParams();
}