<?php

/*
  Plugin Name: Provincias de Ecuador para WooCommerce
  Plugin URI: http://domo.ec
  Description: Este plugin activa las Provincias de Ecuador para WooCommerce.
  Version: 1.1
  Author: Iván Campaña <ivan.campana@domo.ec>
  Author URI: http://www.domo.ec
  License: GPLv3
  Requires at least: 3.0 +
  Tested up to: 5.1
 */
/*
 *      Copyright 2017 Iván Campaña <ivan.campana@domo.ec>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

function provincias_ecuador($states) {
    $states['EC'] = array(
      '01' => 'Azuay',
      '02' => 'Bolívar',
      '03' => 'Cañar',
      '04' => 'Carchi',
      '05' => 'Cotopaxi',
      '06' => 'Chimborazo',
      '07' => 'El Oro',
      '08' => 'Esmeraldas',
      '09' => 'Guayas',
      '10' => 'Imbabura',
      '11' => 'Loja',
      '12' => 'Los Ríos',
      '13' => 'Manabí',
      '14' => 'Morona Santiago',
      '15' => 'Napo',
      '16' => 'Pastaza',
      '17' => 'Pichincha',
      '18' => 'Tungurahua',
      '19' => 'Zamora Chinchipe',
      '20' => 'Galápagos',
      '21' => 'Sucumbíos',
      '22' => 'Orellana',
      '23' => 'Santo Domingo de los Tsachilas',
      '24' => 'Santa Elena',
    );

    return $states;
}

function provincias_ecuador_checkout( $fields ) {
    $province_placeholder = 'Seleccione una Provincia';
    $province_name = 'Provincia';
     $fields['billing']['billing_state']['placeholder'] = $province_placeholder;
     $fields['billing']['billing_state']['label'] = $province_name;

     $fields['shipping']['shipping_state']['placeholder'] = $province_placeholder;
     $fields['shipping']['shipping_state']['label'] = $province_name;

     return $fields;
}

add_filter('woocommerce_checkout_fields' , 'provincias_ecuador_checkout');
add_filter('woocommerce_states', 'provincias_ecuador');
