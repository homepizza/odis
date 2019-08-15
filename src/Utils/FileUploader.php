<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface AS TokenGen;

class FileUploader
{
    private $logger;
    private $fl;
    private $rstack;
    private $token;

    public function __construct(LoggerInterface $logger, Filesystem $fl, RequestStack $rstack, TokenGen $token)
    {
        $this->logger = $logger;
        $this->fl = $fl;
        $this->rstack = $rstack;
        $this->token = $token;
    }

    /**
     * Загрузка файла в директорию uploads.
     *
     * @param $dir
     * @param $file
     * @param $filename
     * @return string
     */
    public function upload($dir, $file, $filename)
    {
        try {
            $exist = $this->fl->exists('../'.$dir);
            if (!$exist) { $this->fl->mkdir('../'.$dir); }
            $existFile = $this->fl->exists('../'.$dir.'/'.$filename);
            if ($existFile) {
                $token = $this->token->generateToken();
                $fileDir = '../'.$dir.'/'.$token;
                $this->fl->mkdir($fileDir);
            }
            $dir = $existFile ? $fileDir : '../'.$dir;
            $file->move($dir, $filename);
        } catch (FileException $e) {
            $this->logger->error('Ошибка загрузки файла' . $e->getMessage());
            throw new FileException('Ошибка загрузки файла');
        }

        $path = $existFile ? '/uploads/'.$token.'/'.$filename : '/uploads/'.$filename;
        return $this->rstack->getCurrentRequest()->getUriForPath($path);
    }

    /**
     * Удаление указанного файла по ссылке на него
     *
     * @param string $link
     */
    public function delete(string $link)
    {
        try {
            $this->fl->remove($link);
        }
        catch (\Exception $e) {
            dump($e->getMessage());
            die();
        }
    }
}