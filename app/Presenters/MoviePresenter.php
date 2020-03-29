<?php declare(strict_types=1);

namespace App\Presenters;

use App\Forms\CommentFormFactory;
use App\Forms\MovieAddFormFactory;
use App\Repository\MovieRepository;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;

class MoviePresenter extends BasePresenter
{

    private MovieRepository $movieRepository;

    private CommentFormFactory $commentFormFactory;

    private MovieAddFormFactory $movieAddFormFactory;

    public function __construct(MovieRepository $movieRepository, CommentFormFactory $commentFormFactory, MovieAddFormFactory $movieAddFormFactory)
    {
        parent::__construct();
        $this->movieRepository = $movieRepository;
        $this->commentFormFactory = $commentFormFactory;
        $this->movieAddFormFactory = $movieAddFormFactory;
    }

    public function renderList(): void
    {
        $this->template->movies = $this->movieRepository->bestMovies();
    }

    public function renderDetail(string $slug)
    {
        $movie = $this->movieRepository->getMovieBySlug($slug);
        $this->template->setParameters([
            'movie' => $movie,
        ]);
    }

    public function renderComment(string $slug)
    {
        if (!$this->user->isLoggedIn()) {
            throw new BadRequestException();
        }
        $this->template->movie = $this->movieRepository->getMovieBySlug($slug);
    }

    public function renderAdd()
    {
        if ($this->user->getRoles()[0] !== 'ROLE_ADMIN') {
            throw new ForbiddenRequestException();
        }
    }

    public function createComponentCommentForm()
    {
        return $this->commentFormFactory->create($this->getParameter('slug'), function () {
            $this->flashMessage('Film úspěšně ohodnocen');
            $this->redirect('Movie:detail', [
                'slug' => $this->getParameter('slug'),
            ]);
        });
    }

    public function createComponentAddForm()
    {
        return $this->movieAddFormFactory->create(function (string $slug) {
            $this->flashMessage('Film úspěšně přidán');
            $this->redirect('Movie:detail', [
                'slug' => $slug,
            ]);
        });
    }

}