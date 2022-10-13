<?php

namespace App\Entity\Query;

use App\Entity\Book;
use App\Repository\BookRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class BookQuery
{
    public function __construct(
        private readonly BookRepository $bookRepository,
    ) {
    }

    /**
     * @return Book[]
     */
    #[GQL\Query(type: '[Book]', name: 'Books')]
    public function BookList(): array
    {
        return $this->bookRepository->findBy([]);
    }
}
