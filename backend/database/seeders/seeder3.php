<?php

namespace DatabaseSeeders;

use IlluminateDatabaseSeeder;
use IlluminateSupportStr;
use AppModelsSala;
use AppModelsCultivo;
use AppModelsCatalogoVariedad;
use AppModelsTipoVariedad;
use AppModelsTipoPlanificacion;
use AppModelsEstadoPlanificacion;
use AppModelsTipoEvento;
use AppModelsEstadoEvento;
use AppModelsTimeLine;
use AppModelsplanificacion;
use AppModelsEvento;
use AppModelsRegistro;
use AppModelsUser;

class CultivoOSSeeder extends Seeder
{
    /**
     * Llena la base de datos con datos de prueba realistas para el Dashboard.
     * Ejecutar con: php artisan db:seed --class=CultivoOSSeeder
     */
    public function run(): void
    {
        // 1. Usuario Operador
        $user = User::firstOrCreate(
            ['email' => 'operador@cultivoos.com'],
            ['name' => 'Ing. Juan Pérez', 'password' => bcrypt('password')]
        );

        // 2. Tipos de Variedad
        $tipoHibrida = TipoVariedad::firstOrCreate(['categoria' => 'HIBRIDA'], ['nombre' => 'Híbrida Equilibrada']);
        $tipoSativa = TipoVariedad::firstOrCreate(['categoria' => 'SATIVA'], ['nombre' => 'Sativa Dominante']);
        $tipoIndica = TipoVariedad::firstOrCreate(['categoria' => 'INDICA'], ['nombre' => 'Índica Pura']);

        // 3. Catálogo de Variedades
        $var1 = CatalogoVariedad::firstOrCreate(
            ['nombre' => 'Gorilla Glue #4'],
            ['banco' => "Barney's Farm", 'tipo_variedad_id' => $tipoHibrida->id, 'thc_porcentaje' => 26.5]
        );
        $var2 = CatalogoVariedad::firstOrCreate(
            ['nombre' => 'Amnesia Haze'],
            ['banco' => 'Royal Queen Seeds', 'tipo_variedad_id' => $tipoSativa->id, 'thc_porcentaje' => 22.0]
        );

        // 4. Estados y Tipos de Planificación y Evento
        $tipoPlanFlora = TipoPlanificacion::firstOrCreate(['nombre' => 'CICLO_FLORACION']);
        $estadoPlanEnCurso = EstadoPlanificacion::firstOrCreate(['nombre' => 'EN_CURSO']);

        $tipoRiego = TipoEvento::firstOrCreate(['nombre' => 'RIEGO_FERTIRRIGACION']);
        $tipoPoda = TipoEvento::firstOrCreate(['nombre' => 'PODA_APICAL']);
        $tipoCalibracion = TipoEvento::firstOrCreate(['nombre' => 'CALIBRACION_SENSORES']);

        $estadoPendiente = EstadoEvento::firstOrCreate(['nombre' => 'PENDIENTE']);
        $estadoRealizado = EstadoEvento::firstOrCreate(['nombre' => 'REALIZADO']);

        // 5. Salas
        $salaA = Sala::firstOrCreate(
            ['codigo' => 'SAL-FLORA-01'],
            [
                'nombre' => 'Sala Floración Alpha',
                'superficie_m2' => 32.5,
                'potencia_luces_w' => 4800,
                'temperatura_objetivo' => 24.5,
                'humedad_objetivo' => 50.0,
                'vpd_objetivo' => 1.25,
                'estado' => 'ACTIVA',
            ]
        );

        // 6. Cultivos
        $cultivo1 = Cultivo::firstOrCreate(
            ['codigo' => 'LOT-2026-GG4'],
            [
                'nombre_lote' => 'Lote 04 - Gorilla Glue #4',
                'sala_id' => $salaA->id,
                'catalogo_variedad_id' => $var1->id,
                'etapa' => 'FLORACION',
                'semana_actual' => 5,
                'semanas_totales_estimadas' => 9,
                'plantas_totales' => 48,
                'fecha_inicio' => now()->subDays(35),
            ]
        );

        // 7. TimeLine y Planificación
        $timeLine = TimeLine::firstOrCreate(
            ['nombre' => 'Timeline Floración 9 Semanas'],
            ['descripcion' => 'Cronograma estándar para híbridas de alto rendimiento']
        );

        $plan = planificacion::firstOrCreate(
            ['nombre' => 'Nutrición Pico de Floración (Semana 5)'],
            [
                'descripcion' => 'Incremento de PK 13/14 y aumento de CO2 a 1200 ppm',
                'duracion_hs' => 168.0,
                'fecha_inicio_planificacion' => now()->toDateString(),
                'fecha_fin_planificacion' => now()->addDays(7)->toDateString(),
                'tipo_planificacion_id' => $tipoPlanFlora->id,
                'estado_planificacion_id' => $estadoPlanEnCurso->id,
                'time_line_id' => $timeLine->id,
            ]
        );

        // 8. Eventos (Asociados al Timeline e Independientes)
        Evento::firstOrCreate(
            ['nombre' => 'Fertirrigación con Boost PK + CalMag'],
            [
                'descripcion' => 'Aplicar 2.2 EC con pH 6.0 ajustado a 20°C de temperatura de solución.',
                'fecha_inicio_planificacion' => now()->addDay()->toDateString(),
                'tipo_evento_id' => $tipoRiego->id,
                'estado_evento_id' => $estadoPendiente->id,
                'cultivo_id' => $cultivo1->id,
                'time_line_id' => $timeLine->id,
                'usuario_id' => $user->id,
            ]
        );

        Evento::firstOrCreate(
            ['nombre' => 'Chequeo Rápido de Calibración Sondas pH/EC'],
            [
                'descripcion' => 'Evento libre imprevisto: comprobación de lecturas en reservorio principal.',
                'fecha_inicio_planificacion' => now()->toDateString(),
                'tipo_evento_id' => $tipoCalibracion->id,
                'estado_evento_id' => $estadoRealizado->id,
                'cultivo_id' => $cultivo1->id,
                'time_line_id' => null, // Evento independiente
                'usuario_id' => $user->id,
            ]
        );

        // 9. Registros Diarios de Telemetría
        Registro::firstOrCreate(
            ['nombre' => 'Telemetría Mediodía - Sala Alpha'],
            [
                'fecha_registro' => now()->toDateTimeString(),
                'descripcion' => 'Parámetros estables. VPD óptimo para fase de engorde.',
                'cultivo_id' => $cultivo1->id,
                'usuario_id' => $user->id,
                'temperatura' => 24.8,
                'humedad' => 52.0,
                'vpd' => 1.28,
                'co2' => 1150.0,
                'temperatura_solucion' => 20.4,
                'temperatura_sustrato' => 21.8,
                'flujo_hora_intraccion' => 450.0,
                'flujo_hora_extracion' => 620.0,
            ]
        );
    }
}
