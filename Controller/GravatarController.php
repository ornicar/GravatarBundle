<?php

namespace Ornicar\GravatarBundle\Controller;


use Ornicar\GravatarBundle\Exception\ImageTransferException;
use Ornicar\GravatarBundle\GravatarApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GravatarController extends Controller
{
    public function getImageAction($hash, $size, $rating, $default, $secure)
    {
        /** @var GravatarApi $gravatar */
        $gravatar = $this->get('gravatar.api');

        try {
            $image = $gravatar->fetchImage($hash, $size, $rating, $default, $secure);
        } catch (ImageTransferException $e) {
            throw new NotFoundHttpException($e->getPrevious()->getMessage());
        }

        $response = new Response(
            $image->getContent(),
            Response::HTTP_OK,
            array(
                'content-type' => $image->getType(),
                'content-length' => $image->getSize(),
            )
        );

        return $response;
    }
} 