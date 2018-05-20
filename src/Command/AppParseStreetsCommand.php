<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class AppParseStreetsCommand extends Command
{
    const FILENAME = 'streets.yml';

    protected static $defaultName = 'app:parse:streets';

    protected $baseUrl = 'https://ato.by';
    protected $startUrl = 'https://ato.by/streets/letter/1';
    protected $path;

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setDescription('Parse streets')
            ->setHelp('This command parses and save streets.');

        $this->path = $this->getRootDir() . '/' . self::FILENAME;
    }

    /**
     * Execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $streets = [];

        // Начало задачи
        $io->writeln('Task starting...');

        $client  = new Client();
        $crawler = $client->request('GET', $this->startUrl);

        // Поиск ссылок
        $links = $crawler->filter('.alphabet a')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        // Проверка наличия ссылок на страницы
        if (empty($links)) {
            $io->warning('Links not found.');

            return;
        }

        // Перебор страниц
        $progress = new ProgressBar($output, count($links));
        $progress->start();
        foreach ($links as $link) {
            $crawler = $client->request('GET', $this->baseUrl.$link);
            // Перебор улиц
            $crawler->filter('.intro > div > ul > li > a')->each(function (Crawler $node) use (&$streets) {
                $streets[] = $this->normalizeStreet($node->text());
            });
            $progress->advance();
        }
        $streets = array_values(array_unique($streets));
        $progress->finish();
        $io->writeln('');

        // Подтверждение сохранения списка
        if (!$io->askQuestion(new ConfirmationQuestion(count($streets) . ' streets parsed, save them into file? ', true))) {
            $io->writeln('Exit without saving.');

            return;
        }

        // Проверка существования файла
        $fs = new Filesystem();
        if ($fs->exists($this->path)) {
            // Выход без сохранения
            $path = realpath($this->path);
            if (!$io->askQuestion(new ConfirmationQuestion("File \"{$path}\" already exists. Replace? ", false))) {
                $io->writeln('Exit without saving.');

                return;
            }
        }

        // Сохранение файла
        $content = Yaml::dump($streets);
        try {
            $fs->dumpFile($this->path, $content);
            $io->success('File saved.');
        } catch (IOException $exception) {
            // Если возникла ошибка
            $io->error($exception->getMessage());
        }
    }

    /**
     * Обработка названий улиц
     *
     * @param string $street
     * @return string
     */
    protected function normalizeStreet(string $street): string
    {
        $street = preg_replace(['/^\s+/', '/\s+$/', '/\s+/'], ['', '', ' '], $street);
        $firstChar = mb_strtoupper(mb_substr($street, 0, 1));

        return $firstChar . mb_substr($street, 1);
    }

    /**
     * @return bool|string
     */
    protected function getRootDir()
    {
        return realpath(__DIR__ . '/../../');
    }
}
