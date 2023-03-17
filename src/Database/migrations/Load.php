<?php

declare(strict_types=1);

namespace micros\splitname\Database\migrations;

use Micros\Splitname\Models\Lesson;
use Micros\Splitname\Models\SplitLesson;
use Micros\Splitname\Models\SplitRule;
use Micros\Splitname\Models\SplitSustitution;
use Micros\Splitname\Models\SplitTerm;
use Micros\Splitname\PartCleaner;

class Load
{
    private $partCleaner;
    public function __construct()
    {
        $this->partCleaner = new PartCleaner();
    }

    public function loadTerms()
    {

        // Male names
        $terms['M'] = [
            'adrián',
            'alejandro',
            'álex',
            'alfonso',
            'álvaro',
            'ángel',
            'antonio',
            'armando',
            'bernardo',
            'carlos',
            'daniel',
            'david',
            'diego',
            'dylan',
            'eduardo',
            'edward',
            'edwin',
            'felipe',
            'fernando',
            'francisco',
            'gabriel',
            'germán',
            'gustavo',
            'hugo',
            'iván',
            'javier',
            'jesús',
            'jhon',
            'johan',
            'jordan',
            'jorge',
            'josé',
            'juan',
            'julián',
            'julio',
            'leo',
            'león',
            'leonardo',
            'lucas',
            'luis',
            'manuel',
            'marcos',
            'martín',
            'mateo',
            'miguel',
            'nicolás',
            'orlando',
            'pablo',
            'pedro',
            'rafael',
            'ricardo',
            'rodrigo',
            'santiago',
            'sergio',
        ];

        // Female names
        $terms['F'] = [
            'adriana',
            'agustina',
            'alba',
            'alicia',
            'ana',
            'ángeles',
            'carla',
            'carmen',
            'claudia',
            'concepción',
            'daniela',
            'elena',
            'elizabeth',
            'ema',
            'emma',
            'fernanda',
            'francisca',
            'gabriela',
            'giovana',
            'giovanna',
            'graciela',
            'guadalupe',
            'helena',
            'irene',
            'josefina',
            'juana',
            'julia',
            'juliana',
            'laura',
            'leidy',
            'leticia',
            'liceth',
            'lucía',
            'lucrecia',
            'luz',
            'magdalena',
            'marcela',
            'margarita',
            'maría',
            'marta',
            'martha',
            'martina',
            'noa',
            'ofelia',
            'patricia',
            'paula',
            'pilar',
            'rosa',
            'sandra',
            'sara',
            'silvia',
            'socorro',
            'sofía',
            'teresa',
            'valentina',
            'valeria',
            'verónica',
            'viviana',
        ];
        // Surnames
        $terms['L'] = [
            'alfonso',
            'alonso',
            'álvarez',
            'ballesteros',
            'bolívar',
            'cabrera',
            'daza',
            'díaz',
            'duque',
            'durán',
            'fajardo',
            'fernández',
            'fonseca',
            'forero',
            'garcía',
            'giraldo',
            'gómez',
            'gonzález',
            'güemes',
            'gutiérrez',
            'hernández',
            'herrera',
            'iglesias',
            'jiménez',
            'león',
            'loboguerrero',
            'lópez',
            'maldonado',
            'martín',
            'martínez',
            'medina',
            'meneses',
            'molina',
            'montoya',
            'morales',
            'moreno',
            'muñoz',
            'ortega',
            'ortíz',
            'peña',
            'peralta',
            'perdomo',
            'pérez',
            'petro',
            'quintana',
            'quiñones',
            'robles',
            'rodríguez',
            'rojas',
            'romero',
            'ruiz',
            'sánchez',
            'sandoval',
            'santamaría',
            'santana',
            'santiago',
            'santos',
            'torre',
            'torres',
            'vega',
            'zuluaga',
        ];

        // Connectors
        $terms['C'] = [
            'de',
            'del',
            'di',
            'el',
            'la',
            'las',
            'los',
            'vda',
            'viuda',
            'y',
            'san',
            'santa',
            'santísima',
            'fray',
            'señor',
            'sr',
            'señora',
            'sra',
            'señorita',
            'srta',
            'doctor',
            'dc',
            'mayor',
            'my',
            'teniente',
            'te',
        ];

        foreach ($terms as $key => $list) {
            foreach ($list as $term) {
                $k = $key;
                $gender = null;
                if ($key === 'M' || $key === 'F') {
                    $k = 'N';
                    $gender = $key;
                }
                $cleanTerm = $this->partCleaner->clean($term);
                // Since the relation [term, key] is unique
                if (!SplitTerm::where('term', $cleanTerm)->where('type', $k)->exists()) {
                    $t = new SplitTerm();
                    $t->term = $cleanTerm;
                    $t->type = $k;
                    $t->gender = $gender;
                    $t->canonical = mb_strtolower($term, 'UTF-8') !== $cleanTerm ? mb_strtolower($term, 'UTF-8') : '';
                    $t->save();
                }
            }
        }
    }
    public function loadRules()
    {

        $rules = [
            // Nombres con un apellido
            'NL' => '13',
            'NNL' => '123',
            'NNNL' => '1223',
            'NCNL' => '1223',
            'NCNNL' => '12223',
            'NCNCNL' => '122223',
            // Nombres con un apellido y conector
            'NCL' => '133',
            'NNCL' => '1233',
            'NNNCL' => '12233',
            'NCNCL' => '12233',
            'NCNNCL' => '122233',
            'NCNCNCL' => '1222233',
            // Nombres con dos apellidos
            'NLL' => '134',
            'NNLL' => '1234',
            'NNNLL' => '12234',
            'NCNLL' => '12234',
            'NCNNLL' => '122234',
            'NCNCNLL' => '1222234',
            // Nombres con dos apellidos y un conector
            'NLCL' => '1344',
            'NNLCL' => '12344',
            'NNNLCL' => '122344',
            'NCNLCL' => '122344',
            'NCNNLCL' => '1222344',
            'NCNCNLCL' => '12222344',
            // Nombres con dos apellidos y dos conectores
            'NCLCL' => '13344',
            'NNCLCL' => '123344',
            'NNNCLCL' => '1223344',
            'NCNCLCL' => '1223344',
            'NCNNCLCL' => '12223344',
            'NCNCNCLCL' => '122223344',
            // Nombres con dos apellidos y conector final
            'NCLL' => '1334',
            'NNCLL' => '12334',
            'NNNCLL' => '122334',
            'NCNCLL' => '122334',
            'NCNNCLL' => '1222334',
            'NCNCNCLL' => '12222334',

            'NNCNLL' => '122234',
            'NLLCL' => '13444',
            'NLLL' => '1344',
            'NNLLL' => '12344',
            'NNNLLL' => '122344',
            'NNLCLL' => '123444',

            'LCLN' => '3441',
            'LCLNN' => '34412',
            'LN' => '31',
            'LNCN' => '3122',
            'LNN' => '312',
            'LLN' => '341',
            'LLNN' => '3412',
            'LNL' => '123',
        ];

        foreach ($rules as $rule => $distribution) {
            if (!SplitRule::where('rule', $rule)->exists() && strlen($rule) === strlen($distribution)) {
                $r = new SplitRule();
                $r->rule = $rule;
                $r->distribution = $distribution;
                $r->save();
            }
        }
    }
    public function loadSustitutions()
    {

        $sustitutions = [
            'LL' => 'NL',
            'LLIL' => 'NNLL',
            'LXNX' => 'LLNN',
            'NCLCX' => 'NCLCL',
            'NCLXL' => 'NCLCL',
            'NCNLCX' => 'NCNLCL',
            'NCNLXL' => 'NCNLCL',
            'NCNX' => 'NCNL',
            'NCNXCL' => 'NCNLCL',
            'NCX' => 'NCL',
            'NCXCL' => 'NCNCL',
            'NCXL' => 'NCNL',
            'NCXLCL' => 'NCNLCL',
            'NCXLX' => 'NCNLL',
            'NCXX' => 'NCNL',
            'NIL' => 'NNL',
            'NILI' => 'NNLL',
            'NILL' => 'NNLL',
            'NLI' => 'NLL',
            'NLN' => 'NLL',
            'NLX' => 'NLL',
            'NLXL' => 'NLCL',
            'NN' => 'NL',
            'NNCNXX' => 'NNCNLL',
            'NNCXCX' => 'NNCLCL',
            'NNLC' => 'NNLL',
            'NNLI' => 'NNLL',
            'NNLN' => 'NNLL',
            'NNLX' => 'NNLL',
            'NNX' => 'NNL',
            'NNXL' => 'NNLL',
            'NNXX' => 'NNLL',
            'NX' => 'NL',
            'NXCX' => 'NNCL',
            'NXL' => 'NLL',
            'NXLL' => 'NNLL',
            'NXLN' => 'NNLL',
            'NXLX' => 'NNLL',
            'NXNLCL' => 'NCNLCL',
            'NXX' => 'NLL',
            'NXXL' => 'NNLL',
            'NXXX' => 'NNLL',
            'XCL' => 'NCL',
            'XCLCL' => 'NCLCL',
            'XCNL' => 'NCNL',
            'XCNLCL' => 'NCNLCL',
            'XCNX' => 'NCNL',
            'XCNXX' => 'NCNLL',
            'XCX' => 'NCL',
            'XCXLCL' => 'NCNLCL',
            'XCXLL' => 'NCNLL',
            'XCXXX' => 'NCNLL',
            'XIL' => 'NNL',
            'XL' => 'NL',
            'XLCL' => 'NLCL',
            'XLL' => 'NLL',
            'XLX' => 'NLL',
            'XNCL' => 'NNCL',
            'XNCX' => 'NNCL',
            'XNL' => 'NNL',
            'XNLI' => 'NNLL',
            'XNLL' => 'NNLL',
            'XNX' => 'NNL',
            'XNXL' => 'NNLL',
            'XNXX' => 'NNLL',
            'XX' => 'NL',
            'XXCL' => 'NLCL',
            'XXL' => 'NNL',
            'XXLL' => 'NNLL',
            'XXLX' => 'NNLL',
            'XXX' => 'NNL',
            'XXXL' => 'NNLL',
            'XXXX' => 'NNLL'
        ];

        foreach ($sustitutions as $origin => $rule) {
            if (!SplitSustitution::where('origin', $origin)->exists() && SplitRule::where('rule', $rule)->exists() && strlen($rule) === strlen($origin)) {
                $r = new SplitSustitution();
                $r->origin = $origin;
                $r->rule = $rule;
                $r->save();
            }
        }
    }
    public function loadLessons()
    {
        $lessons = [
            'LCLNX' => 'N',
            'LCLXN' => 'N',
            'LLNX' => 'N',
            'LLXN' => 'N',
            'LNCX' => 'N',
            'LXNN' => 'L',
            'NCNLCX' => 'L',
            'NCNLX' => 'L',
            'NCNXCL' => 'L',
            'NCNXL' => 'L',
            'NCXLCL' => 'N',
            'NCXLL' => 'N',
            'NLX' => 'L',
            'NNLCX' => 'L',
            'NNLX' => 'L',
            'NNX' => 'L',
            'NNXCL' => 'L',
            'NNXL' => 'L',
            'NXLCL' => 'N',
            'NXLL' => 'N',
            'XCNLCL' => 'N',
            'XCNLL' => 'N',
            'XLL' => 'N',
            'XLNN' => 'L',
            'XNL' => 'N',
            'XNLCL' => 'N',
            'XNLL' => 'N',
        ];
        foreach ($lessons as $rule => $type) {
            if (!SplitLesson::where('rule', $rule)->exists()) {
                $l = new SplitLesson();
                $l->rule = $rule;
                $l->type = $type;
                $l->save();
            }
        }
    }
    public function loadSamples()
    {
    }
}
