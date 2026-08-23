<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Organizacion;
use App\Models\TipoSala;
use App\Models\EstadoSala;
use App\Models\Sala;
use App\Models\Cultivo;
use App\Models\FaseCultivo;
use App\Models\EstadoCultivo;
use App\Models\CultivoVariedad;
use App\Models\Planta;


use App\Models\TipoEquipamiento;
use App\Models\Equipamiento;
use App\Models\CultivoEquipamiento;
use App\Models\CatalogoVariedad;
use App\Models\TipoVariedad;
use App\Models\TipoPlanificacion;
use App\Models\EstadoPlanificacion;
use App\Models\TipoEvento;
use App\Models\EstadoEvento;
use App\Models\TimeLine;
use App\Models\Planificacion;
use App\Models\PlanificacionTasks;
use App\Models\Evento;
use App\Models\Registro;
use App\Models\ConfiguracionRiegoCultivo;
use App\Models\EventoRiegoFertirriego;
use App\Models\User;

class CultivoOSSeeder extends Seeder
{
    /**
     * Llena la base de datos con datos de prueba realistas para CultivoOS.
     * Ejecutar con: php artisan db:seed --class=CultivoOSSeeder
     */
    public function run(): void
    {
        // 1. Usuario Operador
        $user = User::firstOrCreate(
            ['email' => 'operador@cultivoos.com'],
            ['nombre' => 'Ing. Juan Pérez', 'password' => bcrypt('password')]
        );

        // 2. Organización Propietaria
        $organizacion = Organizacion::firstOrCreate(
            ['nombre' => 'CultivoOS Master Facility'],
            [
            
                'descripcion' => 'loremimpso',
            ]
        );

        // 3. Tipos y Estados de Sala (Lookups Relacionales)
        $tipoSalaFlora = TipoSala::firstOrCreate(
            ['nombre' => 'FLORACION'],
            ['descripcion' => 'Sala de Floración con fotoperíodo 12/12 y control estricto de VPD']
        );
        $tipoSalaVeg = TipoSala::firstOrCreate(
            ['nombre' => 'VEGETACION'],
            ['descripcion' => 'Sala de Crecimiento Vegetativo, Madres y Esquejes 18/6']
        );
        $tipoSalaSecado = TipoSala::firstOrCreate(
            ['nombre' => 'SECADO'],
            ['descripcion' => 'Sala de Secado y Curado con HR 55-60% y Temp 18-20°C']
        );

        $estadoSalaActiva = EstadoSala::firstOrCreate(
            ['nombre' => 'ACTIVA'],
            ['descripcion' => 'Sala en plena operación productiva']
        );
        $estadoSalaMantenimiento = EstadoSala::firstOrCreate(
            ['nombre' => 'MANTENIMIENTO'],
            ['descripcion' => 'Sala en tareas de desinfección o ajuste de equipamiento']
        );

        // 4. Tipos de Equipamiento (Lookup relacional extensible)
        $tipoLuminaria = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'LUMINARIA'],
            ['nombre' => 'Luminaria / Iluminación', 'descripcion' => 'Paneles LED, HPS, LEC, Barras Quantum']
        );
        $tipoAC = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'AIRE_ACONDICIONADO'],
            ['nombre' => 'Aire Acondicionado / Climatización', 'descripcion' => 'Splits, Inverters y Rooftops']
        );
        $tipoCO2 = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'CO2'],
            ['nombre' => 'Inyección de CO2', 'descripcion' => 'Generadores y dosificadores de gas']
        );
        $tipoVentilador = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'VENTILADOR'],
            ['nombre' => 'Ventilador / Circulación', 'descripcion' => 'Ventiladores oscilantes de pared y piso']
        );
        $tipoHumidificador = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'HUMIDIFICADOR'],
            ['nombre' => 'Humidificador Ultrasónico', 'descripcion' => 'Aumento de humedad relativa']
        );
        $tipoDeshum = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'DESHUMIDIFICADOR'],
            ['nombre' => 'Deshumidificador Industrial', 'descripcion' => 'Extracción de humedad en floración']
        );
        $tipoRiego = TipoEquipamiento::firstOrCreate(
            ['codigo' => 'RIEGO_AUTOMATICO'],
            ['nombre' => 'Sistema de Riego / Fertirriego', 'descripcion' => 'Bombas, manifolds, goteros y tuberías']
        );

        // 5. Tipos de Variedad & Catálogo
        $tipoHibrida = TipoVariedad::firstOrCreate(['categoria' => 'HIBRIDA'], ['nombre' => 'Híbrida Equilibrada']);
        $tipoSativa = TipoVariedad::firstOrCreate(['categoria' => 'SATIVA'], ['nombre' => 'Sativa Dominante']);

        $var1 = CatalogoVariedad::firstOrCreate(
            ['nombre' => 'Gorilla Glue #4'],
            ['banco' => "Barney's Farm", 'tipo_variedad_id' => $tipoHibrida->id, 'thc_porcentaje' => 26.5,'dias_ciclo'=> 70 ]
        );
        $var2 = CatalogoVariedad::firstOrCreate(
            ['nombre' => 'Amnesia Haze'],
            ['banco' => 'Royal Queen Seeds', 'tipo_variedad_id' => $tipoSativa->id, 'thc_porcentaje' => 22.0,'dias_ciclo'=> 70 ]
        );
// 6. Salas Físicas (Conforme al Modelo App\Models\Sala)
        $salaFlora = Sala::firstOrCreate(
            ['codigo' => 'SAL-FLORA-01'],
            [
                'nombre' => 'Sala Floración Alpha',
                'area' => 32.50,
                'altura' => 3.20,
                'volumen' => 104.00, // 32.5 * 3.2
                'posee_intraccion' => true,
                'posee_extraccion' => true,
                'posee_co2' => true,
                'posee_ins_agua' => true,
                'tipo_sala_id' => $tipoSalaFlora->id,
                'estado_sala_id' => $estadoSalaActiva->id,
                'organizacion_id' => $organizacion->id,
            ]
        );

        $salaVeg = Sala::firstOrCreate(
            ['codigo' => 'SAL-VEG-02'],
            [
                'nombre' => 'Sala Vegetación Beta',
                'area' => 18.00,
                'altura' => 2.80,
                'volumen' => 50.40,
                'posee_intraccion' => true,
                'posee_extraccion' => true,
                'posee_co2' => false,
                'posee_ins_agua' => true,
                'tipo_sala_id' => $tipoSalaVeg->id,
                'estado_sala_id' => $estadoSalaActiva->id,
                'organizacion_id' => $organizacion->id,
            ]
        );

        $salaSecado = Sala::firstOrCreate(
            ['codigo' => 'SAL-SEC-03'],
            [
                'nombre' => 'Sala Secado Delta',
                'area' => 12.00,
                'altura' => 2.60,
                'volumen' => 31.20,
                'posee_intraccion' => true,
                'posee_extraccion' => true,
                'posee_co2' => false,
                'posee_ins_agua' => false,
                'tipo_sala_id' => $tipoSalaSecado->id,
                'estado_sala_id' => $estadoSalaActiva->id,
                'organizacion_id' => $organizacion->id,
            ]
        );

        // 7. Equipamientos (Propiedad física de la Sala via sala_id)
        $eqLed = Equipamiento::firstOrCreate(
            ['codigo_inventario' => 'EQ-LED-FLORA-01'],
            [
                'sala_id' => $salaFlora->id,
                'tipo_equipamiento_id' => $tipoLuminaria->id,
                'nombre' => 'Array LED Samsung LM301H (6x 680W)',
                'marca' => 'Gavita Pro',
                'modelo' => '1700e LED ML',
                'especificaciones' => [
                    'potencia_w' => 4080,
                    'tipo_iluminacion' => 'LED',
                    'espectro' => 'FULL_SPECTRUM',
                    'temperatura_color' => 3500,
                    'ppfd' => 1800,
                    'dimmer' => true,
                    'area_cobertura' => 24.0,
                ],
                'estado' => 'OPERATIVO',
                'calibrado' => true,
                'fecha_ultima_calibracion' => now()->subDays(15)->toDateString(),
            ]
        );

        $eqAC = Equipamiento::firstOrCreate(
            ['codigo_inventario' => 'EQ-CLIMA-01'],
            [
                'sala_id' => $salaFlora->id,
                'tipo_equipamiento_id' => $tipoAC->id,
                'nombre' => 'Inverter Clima Industrial 36000 BTU',
                'marca' => 'Daikin',
                'modelo' => 'Inverter VRV-X',
                'especificaciones' => [
                    'btu' => 36000,
                    'tipo' => 'SPLIT_INVERTER',
                    'frio_calor' => true,
                    'refrigerante' => 'R410A',
                ],
                'estado' => 'OPERATIVO',
                'calibrado' => true,
                'fecha_ultima_calibracion' => now()->subMonths(2)->toDateString(),
            ]
        );

        $eqRiego = Equipamiento::firstOrCreate(
            ['codigo_inventario' => 'EQ-RIEGO-01'],
            [
                'sala_id' => $salaFlora->id,
                'tipo_equipamiento_id' => $tipoRiego->id,
                'nombre' => 'Sistema de Riego por Pulsos de Precisión',
                'marca' => 'Netafim',
                'modelo' => 'Woodpecker Junior CNL',
                'especificaciones' => [
                    'caudal_lph' => 480,
                    'numero_emisores' => 48,
                    'tipo_goteo' => 'SUPERFICIAL',
                    'presion_bar' => 2.2,
                ],
                'estado' => 'OPERATIVO',
                'calibrado' => true,
                'fecha_ultima_calibracion' => now()->subDays(3)->toDateString(),
            ]
        );

        $eqDeshum = Equipamiento::firstOrCreate(
            ['codigo_inventario' => 'EQ-DESH-01'],
            [
                'sala_id' => $salaFlora->id,
                'tipo_equipamiento_id' => $tipoDeshum->id,
                'nombre' => 'Deshumidificador Quest 155 Pints/Day',
                'marca' => 'Quest',
                'modelo' => '155 Overhead',
                'especificaciones' => [
                    'extraccion_l_dia' => 73,
                    'flujo_m3h' => 680,
                ],
                'estado' => 'OPERATIVO',
                'calibrado' => true,
            ]
        );

        // 8. CULTIVO PRINCIPAL (App\Models\Cultivo)
        // NOTA: Cultivo NO contiene catalogo_variedad_id directamente en su tabla.
        // La vinculación se realiza a través de cultivo_variedads (1 Cultivo a N CultivoVariedad).
        $faseFloracion = FaseCultivo::firstOrCreate(['codigo' => 'FLORA_PLENA'], ['nombre' => 'Floración Plena', 'fotoperiodo_sugerido' => '12/12']);
        $estadoCultivoActivo = EstadoCultivo::firstOrCreate(['nombre' => 'ACTIVO'], ['color' => 'emerald']);

        $cultivo1 = Cultivo::firstOrCreate(
            ['codigo' => 'LOT-2026-GG4'],
            [
                'nombre' => 'Lote 04 - Cultivo Multivarietal Sala Flora',
                'sala_id' => $salaFlora->id,
                'fase_id' => $faseFloracion->id,
                'estado_cultivo_id' => $estadoCultivoActivo->id,
                'usuario_responsable_id' => $user->id,
                'duracion_estimada' => 63.00,
                'tipo_sistema_cultivo' => 'SUSTRATO',
                'tipo_sustrato' => 'Coco 70% / Perlita 30%',
                'fecha_inicio' => now()->subDays(35),
                'descripcion' => 'Lote comercial de floración con fertirriego automatizado y múltiples variedades genéticas.',
            ]
        );

        // 8b. CULTIVO_VARIEDADS (App\Models\CultivoVariedad)
        // Permite tener un cultivo con múltiples variedades asociadas a catalogo_variedad_id
        $cultivoVar1 = CultivoVariedad::firstOrCreate(
            [
                'cultivo_id' => $cultivo1->id,
                'catalogo_variedad_id' => $var1->id, // Gorilla Glue #4
            
            
                'cantidad_plantas' => 32,
                'fecha_inicio' => now()->subDays(35),
                'descripcion' => 'Lote principal de fenotipo GG4-Resina Max',
            ]
        );

        $cultivoVar2 = CultivoVariedad::firstOrCreate(
            [
                'cultivo_id' => $cultivo1->id,
                'catalogo_variedad_id' => $var2->id, // Amnesia Haze
            
                'cantidad_plantas' => 16,
                'fecha_inicio' => now()->subDays(35),
                'descripcion' => 'Sector de prueba multivarietal con Amnesia Haze',
            ]
        );

        // 8c. GENERACIÓN DE PLANTAS INDIVIDUALES (App\Models\Planta)
        // Cada planta se vincula a su respectiva CultivoVariedad mediante 'cultivo_variedad_id'
        for ($i = 1; $i <= 32; $i++) {
            Planta::firstOrCreate(
                ['codigo_planta' => sprintf('PLT-GG4-%03d', $i)],
                [
                    'cultivo_id' => $cultivo1->id,
                    'cultivo_variedad_id' => $cultivoVar1->id,
                    'numero_maceta' => $i,
                    'salud' => 'OPTIMO',
                    'estado' => 'EN_DESARROLLO',
                    'posicion' => 'Fila ' . ceil($i / 8) . ' - Pos ' . (($i - 1) % 8 + 1),
                    'fecha_inicio' => now()->subDays(35),
                ]
            );
        }

        for ($j = 1; $j <= 16; $j++) {
            Planta::firstOrCreate(
                ['codigo_planta' => sprintf('PLT-AMN-%03d', $j)],
                [
                    'cultivo_id' => $cultivo1->id,
                    'cultivo_variedad_id' => $cultivoVar2->id,
                    'numero_maceta' => 32 + $j,
                    'salud' => 'OPTIMO',
                    'estado' => 'EN_DESARROLLO',
                    'posicion' => 'Fila 5 - Pos ' . $j,
                    'fecha_inicio' => now()->subDays(35),
                ]
            );
        }

        // 9. Asignación Histórica & Activa de Equipamientos al Cultivo (cultivo_equipamientos)
        // Regla: Solo equipos pertenecientes a la misma sala ($salaFlora->id)
        $equiposAsignados = [$eqLed, $eqAC, $eqRiego, $eqDeshum];
        foreach ($equiposAsignados as $eq) {
            CultivoEquipamiento::firstOrCreate(
                [
                    'cultivo_id' => $cultivo1->id,
                    'equipamiento_id' => $eq->id,
                ],
                [
                    'fecha_inicio' => now()->subDays(35),
                    'fecha_fin' => null, // null = Asignación activa
                    'notas' => 'Asignado al inicio del ciclo de floración.',
                ]
            );
        }

        // 10. Configuración de Riego del Cultivo (JSONB y Nullable)
        ConfiguracionRiegoCultivo::firstOrCreate(
            ['cultivo_id' => $cultivo1->id],
            [
                'estrategia' => 'VWC_HUMEDAD_SUSTRATO',
                'metodo_aplicacion' => 'GOTEO_SUPERFICIAL',
                'tipo_sustrato' => 'COCO_70_PERLITA_30',
                'volumen_maceta_l' => 15.0,
                'tipo_maceta' => 'GEOTEXTIL',
                'fertilizantes_receta' => [
                    ['fertilizante' => 'Coco A Base', 'ml_l' => 2.5],
                    ['fertilizante' => 'Coco B Base', 'ml_l' => 2.5],
                    ['fertilizante' => 'CalMag Plus', 'ml_l' => 0.8],
                    ['fertilizante' => 'PK 13/14 Booster', 'ml_l' => 1.2],
                ],
                'vwc_minimo_pct' => 40.0,
                'vwc_objetivo_pct' => 55.0,
                'vwc_maximo_pct' => 65.0,
                'histeresis_pct' => 5.0,
                'drenaje_objetivo_pct' => 18.0,
                'ec_entrada_objetivo_ms' => 2.2,
                'ec_drenaje_max_ms' => 2.8,
                'ph_entrada_objetivo' => 5.85,
                'pulsos_diarios_sugeridos' => 4,
                'volumen_por_pulso_ml' => 400,
                'volumen_max_diario_l' => 76.8,
                'modo_automatico' => true,
                'modo_pausado' => false,
            ]
        );

        // 11. TimeLine, Planificación y Tareas
        $timeLine = TimeLine::firstOrCreate(
            ['nombre' => 'Timeline Floración 9 Semanas'],
            ['descripcion' => 'Cronograma integral del lote',
                'cultivo_id' => $cultivo1->id]

        );

        $tipoPlanFlora = TipoPlanificacion::firstOrCreate(['nombre' => 'CICLO_FLORACION']);
        $estadoPlanEnCurso = EstadoPlanificacion::firstOrCreate(['nombre' => 'EN_CURSO']);

        $plan = Planificacion::firstOrCreate(
            ['nombre' => 'Nutrición Pico de Floración (Semana 5)'],
            [
                'cultivo_id' => $cultivo1->id,
                'descripcion' => 'Incremento de PK 13/14 y aumento de CO2 a 1200 ppm',
                'duracion_hs' => 168.0,
                'fecha_inicio_planificacion' => now()->toDateString(),
                'fecha_fin_planificacion' => now()->addDays(7)->toDateString(),
                'tipo_planificacion_id' => $tipoPlanFlora->id,
                'estado_planificacion_id' => $estadoPlanEnCurso->id,
                'time_line_id' => $timeLine->id,
            ]
        );

        // 12. Eventos y Tareas Asociadas
        $tipoRiegoEv = TipoEvento::firstOrCreate(['nombre' => 'RIEGO_FERTIRRIGACION']);
        $estadoPendiente = EstadoEvento::firstOrCreate(['nombre' => 'PENDIENTE']);

        $eventoRiego = Evento::firstOrCreate(
            ['nombre' => 'Fertirrigación con Boost PK + CalMag'],
            [
                'descripcion' => 'Aplicar 2.2 EC con pH 5.85 ajustado a 20°C.',
                'fecha_inicio_planificacion' => now()->addDay()->toDateString(),
                'fecha_fin_planificacion' => now()->addDay()->toDateString(),
                'tipo_evento_id' => $tipoRiegoEv->id,
                'estado_evento_id' => $estadoPendiente->id,
                'cultivo_id' => $cultivo1->id,
                'time_line_id' => $timeLine->id,
                'usuario_id' => $user->id,
            ]
        );

        // Tareas de la Planificación (con completada y evento_asociado_id)
        PlanificacionTasks::firstOrCreate(
            [
                'planificacion_id' => $plan->id,
                'titulo' => 'Riego de Engorde con PK Booster',
            ],
            [
                'descripcion' => 'Ejecutar pulso matutino a 2.2 EC tras encendido de luminarias.',
                'semana_sugerida' => 5,
                'dia_ciclo_sugerido' => 35,
                'duracion_estimada_hs' => 1.5,
                'tipo_accion' => 'RIEGO_FERTIRRIEGO',
                'completada' => false,
                'evento_asociado_id' => $eventoRiego->id,
            ]
        );

        PlanificacionTasks::firstOrCreate(
            [
                'planificacion_id' => $plan->id,
                'titulo' => 'Defoliación de Bajeras y Chupones',
            ],
            [
                'descripcion' => 'Lollipop final en tercio inferior para maximizar flujo de aire.',
                'semana_sugerida' => 5,
                'dia_ciclo_sugerido' => 36,
                'duracion_estimada_hs' => 3.0,
                'tipo_accion' => 'DEFOLIACION',
                'completada' => true,
                'evento_asociado_id' => null,
            ]
        );
    }
    }
