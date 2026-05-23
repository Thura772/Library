<?php

require_once BASE_PATH . '/Repository/BaseRepository.php';
require_once BASE_PATH . '/Contract/CatalogRepositoryInterface.php';

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
   
    /*
     * COUNT (BaseRepositoryInterface)
     */


    /*
     * GET BY CATEGORY
     */
    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        $stmt = $this->db->prepare("CALL sp_get_catalog(?, ?, ?)");

        $stmt->bindValue(1, $category, PDO::PARAM_STR);
        $stmt->bindValue(2, $limit, $limit === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $stmt->closeCursor();

        return $data;
    }
    function getcatalog_count($category = null, $search = null)
    {
        $search = !empty($search) ? $search : null;
        $category = !empty($category) ? $category : null;

        $result = $this->db->prepare(" CALL sp_search_catalog_count (:search , :category)");

        $result->bindValue(
            ':search',
            $search,
            $search === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $result->bindValue(
            ':category',
            $category,
            $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $result->execute();

        $count = $result->fetchColumn();

        $result->nextRowset();
        $result->closeCursor();

        return $count;
    }

    /*
     * SEARCH
     */
    public function search(string $keyword, ?string $category = null, ?int $limit = null, int $offset = 0): array
    {
        $keyword = $keyword === '' ? null : $keyword;
        $category = $category === '' ? null : $category;

        $stmt = $this->db->prepare("CALL sp_search_catalog(?, ?, ?, ?)");

        $stmt->bindValue(1, $keyword, $keyword ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(2, $category, $category ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(3, $limit, PDO::PARAM_INT);
        $stmt->bindValue(4, $offset, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetchAll();
        $stmt->nextRowset();
        $stmt->closeCursor();

        return $data;
    }

    /*
     * RANDOM
     */
    public function getRandom(): array
    {
        $stmt = $this->db->query("SELECT * FROM view_random");
        return $stmt->fetchAll();
    }
}