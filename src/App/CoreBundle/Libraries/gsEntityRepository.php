<?php

namespace App\CoreBundle\Libraries;

use Doctrine\ORM\EntityRepository;


class gsEntityRepository extends EntityRepository
{
    use TraitCachingRepository;

    /**
     * getList
     *
     * @param $paginator
     * @param int $page
     * @param int $perPage
     * @param string $sidx
     * @param string $sort
     * @return mixed
     */
    public function getList($paginator, $page = 1, $perPage = 10, $sidx = "a.id", $sort = "asc")
    {

        if (!$sidx) {
            $sidx = "a.id";
        }

        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM '.$this->getTableName().' a
                    ORDER BY '.$sidx.'
        '
            );

        return $paginator->paginate(
            $query,
            $page,
            $perPage,
            array(
                'sort_field_name' => $sidx, // sort field query parameter name
                'sort_direction_name' => $sort     // sort direction query parameter name
            )
        );
    }

    /**
     * save
     *
     * @param $object
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($object)
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($object);
            $em->flush();

            return $object;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * getMaxSequence
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMaxSequence()
    {

        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM '.$this->getTableName().' p 
                                                    ORDER BY p.sequence DESC'
            );
        $query->setMaxResults(1);

        $result = $query->getOneOrNullResult();

        if ($result) {
            return ($result->getSequence() + 1);
        } else {
            return 1;
        }
    }

    /**
     * multiDelete
     *
     * @param $array
     * @return bool
     */
    public function multiDelete($array)
    {
        try {
            if ($array) {
                foreach ($array as $key => $value) {
                    $this->delete($value);
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * delete
     *
     * @param $object
     * @return bool
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($object)
    {
        $em = $this->getEntityManager();

        try {
            if (is_object($object)) {
                $em->remove($object);
                $em->flush();
            } else {
                $model = $this->findOneBy(array("id" => $object));
                $em->remove($model);
                $em->flush();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName()
    {
        $name = $this->getEntityName();

        $name = explode("\\", $name);

        return $name[0].$name[1].":".$name[3];

    }
}

