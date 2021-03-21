<?php

declare(strict_types=1);

namespace App\Bridge\Glide\Bundle\Controller;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Server;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResizeImageController
{
    private Server $server;

    private ?string $signKey;

    public function __construct(Server $server, ?string $signKey = null)
    {
        $this->server = $server;
        $this->signKey = $signKey;
    }

    public function __invoke(Request $request, string $path): Response
    {
        if (null !== $this->signKey) {
            try {
                SignatureFactory::create($this->signKey)
                    ->validateRequest(urldecode($request->getPathInfo()), $request->query->all())
                ;
            } catch (SignatureException $e) {
                throw new AccessDeniedHttpException('Invalid image signature', $e);
            }
        }

        try {
            return $this->server->getImageResponse($path, $request->query->all());
        } catch (FileNotFoundException $exception) {
            throw new NotFoundHttpException(sprintf('Image at path "%s" not found', $path));
        }
    }
}
