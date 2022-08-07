<?php

namespace App\Crawlers;

use App\Models\File;
use App\Models\FileUser;
use App\Models\Source;
use App\Models\Thumbnail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShutterstockCrawler extends SourceCrawler
{

    protected $aspect_ratio_min;
    protected $aspect_ratio_max;
    protected $aspect_ratio;
    protected $category;
    protected $color;
    protected $contributor_country;
    protected $fields;
    protected $height_from;
    protected $height_to;
    protected $image_type;
    protected $keyword_safe_search;
    protected $language;
    protected $orientation;
    protected $page;
    protected $per_page;
    protected $query;
    protected $sort;
    protected $width_from;
    protected $width_to;
    protected $image_id;

    /**
     * @param mixed $aspect_ratio_min
     */
    public function setAspectRatioMin($aspect_ratio_min): void
    {
        $this->aspect_ratio_min = $aspect_ratio_min;
    }

    /**
     * @param mixed $aspect_ratio_max
     */
    public function setAspectRatioMax($aspect_ratio_max): void
    {
        $this->aspect_ratio_max = $aspect_ratio_max;
    }

    /**
     * @param mixed $aspect_ratio
     */
    public function setAspectRatio($aspect_ratio): void
    {
        $this->aspect_ratio = $aspect_ratio;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @param mixed $contributor_country
     */
    public function setContributorCountry($contributor_country): void
    {
        $this->contributor_country = $contributor_country;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param mixed $height_from
     */
    public function setHeightFrom($height_from): void
    {
        $this->height_from = $height_from;
    }

    /**
     * @param mixed $height_to
     */
    public function setHeightTo($height_to): void
    {
        $this->height_to = $height_to;
    }

    /**
     * @param mixed $image_type
     */
    public function setImageType($image_type): void
    {
        $this->image_type = $image_type;
    }

    /**
     * @param mixed $keyword_safe_search
     */
    public function setKeywordSafeSearch($keyword_safe_search): void
    {
        $this->keyword_safe_search = $keyword_safe_search;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language): void
    {
        $this->language = $language;
    }

    /**
     * @param mixed $orientation
     */
    public function setOrientation($orientation): void
    {
        $this->orientation = $orientation;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }

    /**
     * @param mixed $per_page
     */
    public function setPerPage($per_page): void
    {
        $this->per_page = $per_page;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @param mixed $width_from
     */
    public function setWidthFrom($width_from): void
    {
        $this->width_from = $width_from;
    }

    /**
     * @param mixed $width_to
     */
    public function setWidthTo($width_to): void
    {
        $this->width_to = $width_to;
    }

    /**
     * @param mixed $image_id
     */
    public function setImageId($image_id): void
    {
        $this->image_id = $image_id;
    }


    public function images()
    {
        $params = [
            'aspect_ratio' => $this->aspect_ratio,
            'category' => $this->category,
            'color' => $this->color,
            'contributor_country' => $this->contributor_country,
            'fields' => $this->fields,
            'height_from' => $this->height_from,
            'height_to' => $this->height_to,
            'image_type' => $this->image_type,
            'keyword_safe_search' => $this->keyword_safe_search,
            'language' => $this->language,
            'orientation' => $this->orientation,
            'page' => $this->page,
            'per_page' => $this->per_page,
            'query' => $this->query,
            'sort' => $this->sort,
            'width_from' => $this->width_from,
            'width_to' => $this->width_to,
        ];

        $options = [
            CURLOPT_URL => "https://api.shutterstock.com/v2/images/search?" . http_build_query($params),
            CURLOPT_USERAGENT => "php/curl",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}"
            ],
            CURLOPT_RETURNTRANSFER => 1
        ];

        $handle = curl_init();
        curl_setopt_array($handle, $options);
        $response = curl_exec($handle);
        curl_close($handle);

        return json_decode($response);
    }

    public function getFirstImage()
    {
        $params = [
            'aspect_ratio' => $this->aspect_ratio,
            'category' => $this->category,
            'color' => $this->color,
            'contributor_country' => $this->contributor_country,
            'fields' => $this->fields,
            'height_from' => $this->height_from,
            'height_to' => $this->height_to,
            'image_type' => $this->image_type,
            'keyword_safe_search' => $this->keyword_safe_search,
            'language' => $this->language,
            'orientation' => $this->orientation,
            'page' => $this->page,
            'per_page' => $this->per_page,
            'query' => $this->query,
            'sort' => $this->sort,
            'width_from' => $this->width_from,
            'width_to' => $this->width_to,
        ];

        $options = [
            CURLOPT_URL => "https://api.shutterstock.com/v2/images/search?" . http_build_query($params),
            CURLOPT_USERAGENT => "php/curl",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}"
            ],
            CURLOPT_RETURNTRANSFER => 1
        ];

        $handle = curl_init();
        curl_setopt_array($handle, $options);
        $response = curl_exec($handle);
        curl_close($handle);

        return json_decode($response);
    }


    public function subscriptionInfo()
    {
        $options = [
            CURLOPT_URL => "https://api.shutterstock.com/v2/user/subscriptions",
            CURLOPT_USERAGENT => "php/curl",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}"
            ],
            CURLOPT_RETURNTRANSFER => 1
        ];

        $handle = curl_init();
        curl_setopt_array($handle, $options);
        $response = curl_exec($handle);
        curl_close($handle);

        return json_decode($response);
    }
}

