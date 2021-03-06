<?php
namespace Bobby\ArraySpecialHelper;

class ArrayHelper implements ArrayHelperContract
{
    /** 使用字符串表示维度设置数组值.如:queryMultidimensionalSet($arr, "[name][][]", $value)
     * @param $array
     * @param string $query
     * @param $value
     */
    public static function queryMultidimensionalSet(&$array, string $query, $value)
    {
        $query = substr($query, 1, -1);
        $queries = explode('][', $query);
        static::dfsQuerySet($array, $queries, $value);
    }

    protected static function dfsQuerySet(&$array, array $queries, $value)
    {
        if (($query = $queries[0]) === '') {
            $query = is_array($array)? count($array): 0;
        }

        if (count($queries) === 1) {
            $array[$query] = $value;
        } else {
            if (!isset($array[$query])) {
                $array[$query] = null;
            }
            array_shift($queries);
            static::dfsQuerySet($array[$query], $queries, $value);
        }
    }

    /** 将数组转为一维表示 转化后格式如:[$prefix . '[name][0][1]' => 'ludy', $prefix . '[name][0][1]' => 'php', $prefix . '[score][0]' => 100, $prefix . '[0]' => 'ok']
     * @param $node
     * @param string $prefix
     * @param $result
     */
    public static function convertKeyToOneDepth($node, string $prefix, &$result)
    {
        if (!is_array($node)) {
            $result[$prefix] = $node;
        } else {
            foreach ($node as $key => $value) {
                static::convertKeyToOneDepth($value, "{$prefix}[$key]", $result);
            }
        }
    }

    /** 使用字符串表示维度查找数组元素 如:queryMultidimensionalSet($arr, "[name][0][0]")
     * @param $array
     * @param string $query
     * @param $value
     */
    public static function queryMultidimensional($array, string $query)
    {
        $query = substr($query, 1, -1);
        $queries = explode('][', $query);
        $temp = $array;
        foreach ($queries as $query) {
            $temp = $temp[$query];
        }
        return $temp;
    }
}