<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Comuna;

class ComunasChileSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Arica y Parinacota' => [
                'Arica','Camarones','Putre','General Lagos',
            ],
            'Tarapacá' => [
                'Iquique','Alto Hospicio','Pozo Almonte','Camiña','Colchane','Huara','Pica',
            ],
            'Antofagasta' => [
                'Antofagasta','Mejillones','Sierra Gorda','Taltal',
                'Calama','Ollagüe','San Pedro de Atacama',
                'Tocopilla','María Elena',
            ],
            'Atacama' => [
                'Copiapó','Caldera','Tierra Amarilla',
                'Chañaral','Diego de Almagro',
                'Vallenar','Alto del Carmen','Freirina','Huasco',
            ],
            'Coquimbo' => [
                'La Serena','Coquimbo','Andacollo','La Higuera','Paihuano','Vicuña',
                'Illapel','Canela','Los Vilos','Salamanca',
                'Ovalle','Combarbalá','Monte Patria','Punitaqui','Río Hurtado',
            ],
            'Valparaíso' => [
                'Valparaíso','Viña del Mar','Concón','Quintero','Puchuncaví','Casablanca','Juan Fernández',
                'San Antonio','Cartagena','El Tabo','El Quisco','Algarrobo',
                'Quillota','La Calera','Hijuelas','La Cruz','Nogales',
                'Quilpué','Villa Alemana','Limache','Olmué',
                'San Felipe','Llay Llay','Catemu','Panquehue','Putaendo','Santa María',
                'Los Andes','Calle Larga','Rinconada','San Esteban',
                'Petorca','Cabildo','La Ligua','Papudo','Zapallar',
                'Isla de Pascua',
            ],
            'Libertador General Bernardo O\'Higgins' => [
                // Provincia de Cachapoal
                'Rancagua','Codegua','Coinco','Coltauco','Doñihue','Graneros','Las Cabras','Machalí','Malloa','Mostazal','Olivar','Peumo','Pichidegua','Quinta de Tilcoco','Rengo','Requínoa','San Vicente',
                // Provincia de Colchagua
                'San Fernando','Chépica','Chimbarongo','Lolol','Nancagua','Palmilla','Peralillo','Placilla','Pumanque','Santa Cruz',
                // Provincia Cardenal Caro
                'Pichilemu','La Estrella','Litueche','Marchigüe','Navidad','Paredones',
            ],
            'Maule' => [
                // Talca
                'Talca','Constitución','Curepto','Empedrado','Maule','Pelarco','Pencahue','Río Claro','San Clemente','San Rafael',
                // Curicó
                'Curicó','Hualañé','Licantén','Molina','Rauco','Romeral','Sagrada Familia','Teno','Vichuquén',
                // Linares
                'Linares','Colbún','Longaví','Parral','Retiro','San Javier','Villa Alegre','Yerbas Buenas',
                // Cauquenes
                'Cauquenes','Chanco','Pelluhue',
            ],
            'Biobío' => [
                // Concepción
                'Concepción','Coronel','Chiguayante','Florida','Hualpén','Hualqui','Lota','Penco','San Pedro de la Paz','Santa Juana','Talcahuano','Tomé',
                // Arauco
                'Arauco','Cañete','Contulmo','Curanilahue','Lebu','Los Álamos','Tirúa',
                // Biobío
                'Los Ángeles','Antuco','Cabrero','Laja','Mulchén','Nacimiento','Negrete','Quilaco','Quilleco','San Rosendo','Santa Bárbara','Tucapel','Yumbel','Alto Biobío',
            ],
            'La Araucanía' => [
                // Cautín
                'Temuco','Carahue','Cholchol','Cunco','Curarrehue','Freire','Galvarino','Gorbea','Lautaro','Loncoche','Melipeuco','Nueva Imperial','Padre Las Casas','Perquenco','Pitrufquén','Pucón','Saavedra','Teodoro Schmidt','Toltén','Vilcún',
                // Malleco
                'Angol','Collipulli','Curacautín','Ercilla','Lonquimay','Los Sauces','Lumaco','Purén','Renaico','Traiguén','Victoria',
            ],
            'Los Ríos' => [
                // Valdivia
                'Valdivia','Corral','Lanco','Los Lagos','Máfil','Mariquina','Paillaco','Panguipulli',
                // Ranco
                'La Unión','Futrono','Lago Ranco','Río Bueno',
            ],
            'Los Lagos' => [
                // Llanquihue
                'Puerto Montt','Calbuco','Cochamó','Maullín','Puerto Varas','Llanquihue','Frutillar','Fresia','Los Muermos',
                // Osorno
                'Osorno','San Juan de la Costa','San Pablo','Puyehue','Río Negro','Purranque',
                // Chiloé
                'Castro','Ancud','Chonchi','Curaco de Vélez','Dalcahue','Puqueldón','Queilén','Quemchi','Quellón','Quinchao',
                // Palena
                'Chaitén','Futaleufú','Hualaihué','Palena',
            ],
            'Aysén' => [
                'Coyhaique','Lago Verde',
                'Aysén','Cisnes','Guaitecas',
                'Cochrane','O\'Higgins','Tortel',
                'Chile Chico','Río Ibáñez',
            ],
            'Magallanes y la Antártica Chilena' => [
                // Magallanes
                'Punta Arenas','Laguna Blanca','Río Verde','San Gregorio',
                // Última Esperanza
                'Natales','Torres del Paine',
                // Tierra del Fuego
                'Porvenir','Primavera','Timaukel',
                // Antártica Chilena
                'Cabo de Hornos','Antártica',
            ],
            'Metropolitana' => [
                // Santiago
                'Santiago','Cerrillos','Cerro Navia','Conchalí','El Bosque','Estación Central','Huechuraba','Independencia','La Cisterna','La Florida','La Granja','La Pintana','La Reina','Las Condes','Lo Barnechea','Lo Espejo','Lo Prado','Macul','Maipú','Ñuñoa','Pedro Aguirre Cerda','Peñalolén','Providencia','Pudahuel','Quilicura','Quinta Normal','Recoleta','Renca','San Joaquín','San Miguel','San Ramón','Vitacura',
                // Cordillera
                'Puente Alto','Pirque','San José de Maipo',
                // Maipo
                'San Bernardo','Buin','Paine','Calera de Tango',
                // Talagante
                'Talagante','El Monte','Isla de Maipo','Padre Hurtado','Peñaflor',
                // Melipilla
                'Melipilla','Alhué','Curacaví','María Pinto','San Pedro',
                // Chacabuco
                'Colina','Lampa','Tiltil',
            ],
            'Ñuble' => [
                'Chillán','Chillán Viejo','Bulnes','Cobquecura','Coelemu','Coihueco','El Carmen','Ninhue','Ñiquén','Pemuco','Pinto','Portezuelo','Quillón','Quirihue','Ránquil','San Carlos','San Fabián','San Ignacio','San Nicolás','Treguaco','Yungay',
            ],
        ];

        foreach ($data as $regionNombre => $comunas) {
            $region = Region::where('nombre', $regionNombre)->first();
            if (!$region) {
                $this->command->warn("Región no encontrada: {$regionNombre} (omite sus comunas)");
                continue;
            }
            foreach ($comunas as $c) {
                Comuna::firstOrCreate([
                    'region_id' => $region->id,
                    'nombre'    => $c,
                ]);
            }
        }
    }
}
