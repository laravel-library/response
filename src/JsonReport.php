<?php

namespace Elephant\Response;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

readonly class JsonReport implements Reportable
{
	public function __construct(private Request $request) {}

	public function report(JsonResponse|Throwable $response): array
	{
		return match ($this->request->getMethod()) {
			'POST', 'PATCH', 'PUT' => $this->render($response->getContent(), 201, 'created'),
			'DELETE'               => $this->render($response->getContent(), 204, 'deleted'),
			default                => $this->render($response->getContent(), 200, 'success')
		};
	}

	private function render(mixed $content, int $status, string $message): array
	{
		$response = ['message' => $message, 'status' => $status, 'data' => null];

		if (json_validate($content)) {
			$response['data'] = json_decode($content, true);
		} else if (is_string($content)) {
			$response['message'] = $content;
		}

		return $response;
	}
}