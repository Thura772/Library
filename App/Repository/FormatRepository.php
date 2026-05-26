<?php

namespace App\Repository;

use PDO;
use App\Contract\FormatRepositoryInterface;

class FormatRepository extends BaseRepository
implements FormatRepositoryInterface
{
    /*
     * FORMAT DROPDOWN
     */
    public function get_format_drop_down(
        $category = null
    ) {

        $stmt = $this->callProcedure(
            'sp_get_formats_by_category',
            [$category]
        );

        $format = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $format[$row['category']][] =
                $row['format'];
        }

        $stmt->closeCursor();

        return $format;
    }

    /*
     * CATEGORY DROPDOWN
     */
    public function get_category_drop_down()
    {
        $stmt = $this->query("
            SELECT DISTINCT category
            FROM view_catalog
            ORDER BY category
        ");

        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt->closeCursor();

        return $categories;
    }

    /*
     * GENRE DROPDOWN
     */
    public function get_genres_drop_down(
        $category = null
    ) {

        $stmt = $this->callProcedure(
            'sp_get_genres_by_category',
            [$category]
        );

        $genre = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row['category']][] =
                $row['genre'];
        }

        $stmt->closeCursor();

        return $genre;
    }
}