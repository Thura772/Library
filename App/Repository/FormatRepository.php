<?php

namespace App\Repository;

use PDO;
use App\Contract\FormatRepositoryInterface;

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    /*
     * FORMAT DROPDOWN
     */
    public function get_format_drop_down($category = null)
    {
        $stmt = $this->db->prepare("CALL sp_get_formats_by_category(:category)");

        $stmt->bindValue(
            ':category',
            $category,
            $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $stmt->execute();

        $format = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $format[$row["category"]][] = $row["format"];
        }

        $stmt->closeCursor();

        return $format;
    }

    /*
     * CATEGORY DROPDOWN
     */
    public function get_category_drop_down()
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT category 
            FROM view_catalog 
            ORDER BY category
        ");

        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt->closeCursor();

        return $categories;
    }

    /*
     * GENRE DROPDOWN
     */
    public function get_genres_drop_down($category = null)
    {
        $stmt = $this->db->prepare("CALL sp_get_genres_by_category(:category)");

        $stmt->bindValue(
            ':category',
            $category,
            $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $stmt->execute();

        $genre = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row["category"]][] = $row["genre"];
        }

        $stmt->closeCursor();

        return $genre;
    }
}
