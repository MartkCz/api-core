<?php declare(strict_types = 1);

namespace Api\Core;

final class ErrorMapping
{

	/**
	 * @param array<int, string> $mapping
	 */
	public function __construct(
		private array $mapping,
	)
	{
	}

	/**
	 * @param mixed[] $values
	 * @return array{errors: list<string>, warnings: list<string>}
	 */
	public function render(array $values): array
	{
		$errors = [];
		$warnings = [];

		foreach ($values as $value) {
			$type = $value['type'] ?? null;
			$code = $value['code'] ?? null;
			$message = $value['message'] ?? null;
			$context = $value['context'] ?? null;

			if ($code === null || $message === null) {
				continue;
			}

			$message = isset($this->mapping[$code]) ? $this->template($this->mapping[$code], is_array($context) ? $context : []) : $message;

			if ($type === 'warning') {
				$warnings[] = $message;
			} else {
				$errors[] = $message;
			}
		}

		return [
			'errors' => $errors,
			'warnings' => $warnings,
		];
	}

	/**
	 * @param mixed[] $parameters
	 */
	private function template(string $template, array $parameters): string
	{
		$replacements = [];

		foreach ($parameters as $key => $value) {
			$replacements['{' . $key . '}'] = $value;
		}

		return strtr($template, $replacements);
	}

}
