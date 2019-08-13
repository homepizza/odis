<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;

class FileUploader
{
    private $logger;
    private $fl;
    private $rstack;

    public function __construct(LoggerInterface $logger, Filesystem $fl, RequestStack $rstack)
    {
        $this->logger = $logger;
        $this->fl = $fl;
        $this->rstack = $rstack;
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
            $file->move('../'.$dir, $filename);
        } catch (FileException $e) {
            $this->logger->error('Ошибка загрузки файла' . $e->getMessage());
            throw new FileException('Ошибка загрузки файла');
        }
        return $this->rstack->getCurrentRequest()->getUriForPath('/uploads/'.$filename);
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