<style>
    .page_pay {
        font-family: 'Cairo', sans-serif;
        line-height: 1;
        color: #000;
        direction: ltr;
        position: relative;

    }

    .page-footer {
        margin-top: 20px;
        text-align: center;
    }

    .barcode-stamp {
        margin-bottom: 10px;
    }

    .barcode-stamp img {
        height: 60px;
        width: auto;
        display: block;
        margin: 0 auto;
    }

    .barcode-stamp .code {
        margin-top: 6px;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 3px;
    }

    .footer-pay {
        font-size: 14px;
        color: #777;
        border-top: 1px dashed #ccc;
        padding-top: 5px;
    }

    #salary-slip {
        padding: 15px;
    }


    .header h4,
    .header h3 {
        margin: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px
    }

    table th,
    table td {
        border: 1px solid #ccc;
        padding: 7px;
        text-align: left;
    }

    .details-table td {
        text-align: left;
        border: none;
    }

    .table-spacing {
        margin-top: 20px;
    }

    .salary-section {
        display: flex;
        gap: 1%;
    }

    .salary-column {
        width: 49.5%;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }


    .salary-column .table-wrapper {
        width: 100%;
    }

    .footer {
        margin-top: 50px;
        text-align: center;
    }

    .footer p {
        margin: 5px 0;
    }


    .highlight {
        font-weight: bold;
        background: #edeff7;
        border: 1px solid #ccc;

    }

    @media print {
        body {
            font-family: 'Cairo', sans-serif;
            margin-bottom: 120px;
            padding: 0px;
            direction: ltr;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            border-top: 1px dashed #ccc;
            background: #fff;
        }

        .barcode-stamp img {
            height: 55px;
            width: auto;
            margin: 0 auto;
        }

        .barcode-stamp .code {
            margin-top: 6px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .footer-pay {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        img,
        svg {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        table th,
        table td {
            border: 1px solid #ccc;
            padding: 1px;
            text-align: left;
        }

        th,
        td {
            border: 1px solid black;
            padding: 1px;
            text-align: left;
        }

        .details-table td {
            text-align: left;
            border: none;
            margin-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .highlight {
            font-weight: bold;
            background: #e0e1e6;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
        }

        .footer p {
            margin: 5px 0;
        }

        .mt-5,
        .my-5 {
            margin-top: 3rem !important;
        }
    }
</style>
<div class="page_pay">
    <!-- Header Section -->
    <div class="text-center">
        <h4>REPUBLIQUE ALGERIENNE DEMOCRATIQUE ET POPULAIRE</h4>
        <h4>MINISTERE DE L'EDUCATION NATIONALE</h4>
    </div>
    <div>
        <h4 style="direction: ltr; text-align: left;">DIRECTION DE L'EDUCATION DE LA WILAYA DE EL-MEGHAIER</h4>
    </div>
    <div class="text-center mb-2">
        <h1>Fiche De Paie {{ $monthName }} {{ $year }}</h1>
    </div>

    <!-- Employee Details -->
    <table class="details-table mt-0">
        <tr>
            <td style="border-bottom: 1px solid #CCC;">
                <strong>Nom :</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->NOM }}
            </td>
            <td style="border-bottom: 1px solid #CCC; ">
                <strong>Prenom :</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->PRENOM }}
            </td>
            <td style="border-bottom: 1px solid #CCC; ">
                <strong>Fonction :</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->grade->namefr ?? 'غير متوفر' }}
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #CCC; ">
                <strong>Situation Famille:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->SITFAM }}
            </td>
            <td style="border-bottom: 1px solid #CCC;">
                <strong>Catégories :</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $rwPaper->CATEG }}
            </td>
            <td style="border-bottom: 1px solid #CCC;">
                <strong>Échelon :</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $rwPaper->ECH }}
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #CCC;">
                <strong>Num CCP :</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->MATRI }}
            </td>
            <td style="border-bottom: 1px solid #CCC;">
                <strong> Num SS :</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->NUMSS }}
            </td>
            <td style="border-bottom: 1px solid #CCC;">
                <strong>Nbre de Jours Travail :</strong>
                &nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($rwPaper['NBRTRAV']) }}
            </td>
        </tr>
    </table>

    <!-- Salary Details -->
    <div class="salary-section">
        <div class="salary-column">
            <div class="table-wrapper">
                <table id="salary-slip">
                    <thead>
                        <tr>
                            <th class="highlight">LES COMPOSITIONS DU SALAIRE</th>
                            <th class="highlight">MONTANT (DA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryDetails as $detail)
                            <tr>
                                <td>{{ $detail['ElementName'] }}</td>
                                <td>{{ number_format($detail['MONTANT'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="mrdiyya-row" style="display:none;">
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <td>Prim Rendement</td>
                                <td>{{ number_format($BRUTMENS, 2) }}</td>
                            @endif
                        </tr>
                        <tr class="net-salary-only">
                            <td class="highlight">BRUTE IMPOSABLE </td>
                            <td class="highlight">{{ number_format($rwPaper['BRUTSS'], 2) }}</td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td class="highlight">BRUTE IMPOSABLE (Paie+Prim)</td>
                                <td class="highlight">
                                    {{ number_format($rwPaper['BRUTSS'] + $BRUTSS, 2) }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="table-wrapper">
                <table id="salary-slip">
                    @if (!empty($allocationFamiliales) && count($allocationFamiliales) > 0)
                        <thead>
                            <tr>
                                <th class="highlight">LES ALLOCATIONS FAMILLIALES</th>
                                <th class="highlight">MONTANT (DA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocationFamiliales as $allocationFamiliale)
                                <tr>
                                    <td>{{ $allocationFamiliale['ElementName'] }}</td>
                                    <td>{{ number_format($allocationFamiliale['MONTANT'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="net-salary-only">
                                <td class="highlight">SALAIRE BRUTE </td>
                                <td class="highlight">{{ number_format($rwPaper['TOTGAIN'], 2) }}</td>
                            </tr>
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <tr class="mrdiyya-row" style="display:none;">
                                    <td class="highlight">SALAIRE BRUTE (Paie+Prim)</td>
                                    <td class="highlight">
                                        {{ number_format($rwPaper['TOTGAIN'] + $TOTGAIN, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @else
                        <tbody>
                            <tr class="net-salary-only">
                                <td class="highlight">SALAIRE BRUTE </td>
                                <td class="highlight">{{ number_format($rwPaper['TOTGAIN'], 2) }}</td>
                            </tr>
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <tr class="mrdiyya-row" style="display:none;">
                                    <td class="highlight">SALAIRE BRUTE (Paie+Prim)</td>
                                    <td class="highlight">
                                        {{ number_format($rwPaper['TOTGAIN'] + $TOTGAIN, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @endif
                </table>
            </div>
        </div>

        <div class="salary-column">
            <div class="table-wrapper">
                <table id="salary-slip">
                    <thead>
                        <tr>
                            <th class="highlight">LES RETENUS</th>
                            <th class="highlight">MONTANT (DA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="net-salary-only">
                            <td>Retenue Sécurité Sociale</td>
                            <td>{{ number_format($rwPaper['RETSS'], 2) }}</td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td>Retenue Sécurité Sociale (Paie+Prim)</td>
                                <td>
                                    {{ number_format($rwPaper['RETSS'] + $RETSS / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr class="net-salary-only">
                            <td>Retenue IRG</td>
                            <td>{{ number_format($rwPaper['RETITS'], 2) }}</td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td>Retenue IRG (Paie+Prim)</td>
                                <td>
                                    {{ number_format($rwPaper['RETITS'] + $RETITS / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($socialServicesDetails as $detail)
                            <tr>
                                <td>{{ $detail['ElementName'] }}</td>
                                <td>{{ number_format($detail['MONTANT'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="net-salary-only">
                            <td>LES RETENUS TOTALS</td>
                            <td class="highlight">
                                {{ number_format($rwPaper['RETITS'] + $rwPaper['RETSS'] + $socialServicesDetails->sum('MONTANT'), 2) }}
                            </td>
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                        <tr class="mrdiyya-row" style="display:none;">
                            <td class="highlight">RETENUS TOTALS (Paie+Prim)</td>
                            <td class="highlight">
                                {{ number_format($rwPaper['RETITS'] + $rwPaper['RETSS'] + $socialServicesDetails->sum('MONTANT') + $RETITS / 3 + $RETSS / 3, 2) }}
                            </td>
                        </tr>
                        @endif
                        </tr>
                    </tbody>
                </table>
                <div class="table-wrapper mt-5">
                    <table id="salary-slip">
                        <!-- صف الراتب الأساسي -->
                        <tr class="net-salary-only">
                            <td class="highlight">NET A PAYE</td>
                            <td class="highlight">
                                {{ number_format($rwPaper['NETPAI'], 2) }}
                            </td>
                        </tr>
                        <!-- صف الراتب + المردودية (مخفي افتراضياً) -->
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td class="highlight">NET A PAYE (Paie+Prim)</td>
                                <td class="highlight">
                                    {{ number_format($rwPaper['NETPAI'] + $mrdiyya / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                    </table>
                    <!-- Footer -->
                    <div class="footer text-center">
                        <p>EL-MEGHAIER : {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                        @if (auth()->check() && auth()->user()->role === 'director')
                            <p>Directeur</p>
                        @elseif (auth()->check() && auth()->user()->role === 'manager')
                            <p>Manager</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="barcode-stamp">
            <img
                src="data:image/png;base64,{{ DNS1D::getBarcodePNG('00799999000' . $employee->MATRI . $employee->CLECPT, 'C128', 2, 50) }}" />
            <div class="code">{{ '00799999000' . $employee->MATRI . $employee->CLECPT }}</div>
        </div>
        <div class="footer-pay text-center mt-5">
            <div style="color: rgba(0,0,0,0.10);">
                Cellule informatique {{ now()->year }} M©B DIRECTION DE L'EDUCATION DE EL-MEGHAIER </div>
        </div>
    </div>
</div>
