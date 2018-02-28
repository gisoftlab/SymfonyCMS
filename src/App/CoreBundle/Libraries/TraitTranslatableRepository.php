<?php

namespace App\CoreBundle\Libraries;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

use Gedmo\Translatable\TranslatableListener;

/**
 * Class TranslatableRepository
 *
 * This is my translatable repository that offers methods to retrieve results with translations
 */
trait TraitTranslatableRepository
{
    /**
     * @var string Default locale
     */
    protected $defaultLocale;

    /**
     * Sets default locale
     *
     * @param string $locale
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * Returns translated one (or null if not found) result for given locale
     *
     * @param QueryBuilder | Query $qb            A Doctrine query builder instance
     * @param string       $locale        A locale name
     * @param string       $hydrationMode A Doctrine results hydration mode
     *
     * @return QueryBuilder
     */
    public function getOneOrNullResult($qb, $locale = null, $hydrationMode = null)
    {
        return $this->getTranslated($qb, $locale)->getOneOrNullResult($hydrationMode);
    }

    /**
     * Returns translated results for given locale
     *
     * @param QueryBuilder | Query $qb            A Doctrine query builder instance
     * @param string       $locale        A locale name
     * @param integer       $hydrationMode A Doctrine results hydration mode
     *
     * @return array
     */
    public function getResult($qb, $locale = null, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        return $this->getTranslated($qb, $locale)->getResult($hydrationMode);
    }

    /**
     * Returns translated array results for given locale
     *
     * @param QueryBuilder | Query $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return array
     */
    public function getArrayResult($qb, $locale = null)
    {
        return $this->getTranslated($qb, $locale)->getArrayResult();
    }

    /**
     * Returns translated single result for given locale
     *
     * @param QueryBuilder | Query $qb            A Doctrine query builder instance
     * @param string       $locale        A locale name
     * @param string       $hydrationMode A Doctrine results hydration mode
     *
     * @return QueryBuilder
     */
    public function getSingleResult($qb, $locale = null, $hydrationMode = null)
    {
        return $this->getTranslated($qb, $locale)->getSingleResult($hydrationMode);
    }

    /**
     * Returns translated scalar result for given locale
     *
     * @param QueryBuilder | Query $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return array
     */
    public function getScalarResult($qb, $locale = null)
    {
        return $this->getTranslated($qb, $locale)->getScalarResult();
    }

    /**
     * Returns translated single scalar result for given locale
     *
     * @param QueryBuilder | Query $qb     A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return QueryBuilder
     */
    public function getSingleScalarResult($qb, $locale = null)
    {
        return $this->getTranslated($qb, $locale)->getSingleScalarResult();
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder $qb A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return Query
     */
    protected function getTranslatedQueryBuilder(QueryBuilder $qb, $locale)
    {
        $locale = null === $locale ? $this->defaultLocale : $locale;

        $query = $qb->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query;
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param Query $query A Doctrine query builder instance
     * @param string | null      $locale A locale name
     *
     * @return Query
     */
    protected function getTranslatedQuery(Query $query, $locale)
    {
        $locale = null === $locale ? $this->defaultLocale : $locale;

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $locale);

        return $query;
    }

    /**
     * Returns translated Doctrine query instance
     *
     * @param QueryBuilder| Query $query     A Doctrine query builder instance
     * @param string       $locale A locale name
     *
     * @return Query
     */

    protected function getTranslated($query, $locale = null)
    {
        if ($query instanceof QueryBuilder) {
            return $this->getTranslatedQueryBuilder($query,$locale);
        }

        else if ($query instanceof Query) {
            return $this->getTranslatedQuery($query,$locale);
        }

    }
}