<?php

if (!function_exists('sort_table_link')) {
    /**
     * Genera la URL de ordenamiento manteniendo los filtros actuales y alternando la dirección.
     *
     * @param string $field Nombre de la columna en la BD.
     * @return string
     */
    function sort_table_link(string $field): string
    {
        $currentSort = request('sort');
        $currentDirection = request('direction', 'asc');

        // Si ya se está ordenando por este campo, alternamos; si no, por defecto es 'asc'
        $nextDirection = ($currentSort === $field && $currentDirection === 'asc') ? 'desc' : 'asc';

        // Fusionamos los parámetros actuales de la URL con el nuevo ordenamiento
        $queryParams = array_merge(request()->query(), [
            'sort' => $field,
            'direction' => $nextDirection,
        ]);

        return route(request()->route()->getName(), $queryParams);
    }
}

if (!function_exists('sort_table_icon')) {
    /**
     * Renderiza el emoji indicador de ordenamiento para la columna activa.
     *
     * @param string $field Nombre de la columna.
     * @return string
     */
    function sort_table_icon(string $field): string
    {
        if (request('sort') === $field) {
            return request('direction') === 'asc' ? ' 🔼' : ' 🔽';
        }

        // Icono por defecto (opcional) cuando la columna "fecha" es la activa por defecto en el controlador
        if (!request('sort') && $field === 'fecha') {
            return request('direction') === 'asc' ? ' 🔼' : ' 🔽';
        }

        return '';
    }
}