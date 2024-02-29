<?php

namespace App\Command;

use App\Service\OmdbApiService;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplaceImagesCommand extends Command
{
    protected static $defaultName = 'app:replace-images';
    protected static $defaultDescription = 'Replace all images by images from OMDBAPI';

    private $omdbApi;
    private $movieRepository;

    public function __construct(OmdbApiService $omdbApi, MovieRepository $movieRepository)
    {
        $this->omdbApi = $omdbApi;
        $this->movieRepository = $movieRepository;

        // le constructeur écrase celui du parent, il est donc necessaire de l'appeller manuellement
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        //  récupérer les films
        $movies = $this->movieRepository->findAll();
        //  boucler dessus
        foreach ($movies as $movie) {
            //  récupérer l'image sur l'api
            $poster = $this->omdbApi->getPoster($movie);
            //  remplacer l'image par celle de l'api ou un placeholder si elle n'existe pas
            if ($poster != "N/A" && $poster != null) {
                $movie->setPoster($poster);
            } else {
                // si l'api ne trouve pas de poster, je mets une image par défaut
                $movie->setPoster("https://m.media-amazon.com/images/M/MV5BMDgzYTM4YWYtMWU2OS00ZTZhLThkYzItOWJiZTIzYmJhOTVmXkEyXkFqcGdeQXVyNjQwMzA5Ng@@._V1_SX300.jpg");
            }
        }
        // je flush ma modif
        $this->movieRepository->add($movie, true);

        $io->success('Bravo ! All images are replaced');

        return Command::SUCCESS;
    }
}
