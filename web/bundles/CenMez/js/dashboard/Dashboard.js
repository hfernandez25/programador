var info;
var fecha = new Date();
var ano = fecha.getFullYear();

function inicio()
{
    //crearGraficoProduccion();
    //crearGraficoEstadoPlagas();
    
    
}

function crearGraficoEstadoPlagas()
{
    var chart = c3.generate({
        bindto: '#chart-estado-plagas',
        data: {
            url: "../informes/sanidad/ConsultaPromedioLarvasHojasDashboard",
            mimeType: 'json',
            type: 'bar',
            order: 'desc',
            keys: {
                x: 'planombre',
                value: ['promedio']
            },
            labels: {
              format: function (v, id, i, j) { return r2d(v);}
            }
        },
        axis: {
          x: {
            type: 'categorized'
          },
          y : {
            tick: {
                format: function (x) { return r2d(x); }
            }
          }
        },
        grid: {
            x: {
                show: false
            },
            y: {
                show: true
            }
        }
    });
    
}

function crearGraficoProduccion()
{
    var chart = c3.generate({
        bindto: '#chartProduccion',
        data: {
            x: 'x',
            url: "../informes/produccion/PresupuestoVsRealPorAnno?anno="+ano,
            mimeType: 'json',
            labels: {
                format: function (v, id, i, j) { format = d3.format("0,000"); return format(v); }
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: '%b'
                }
            },
            y : {
                tick: {
                    format: function (x) { format = d3.format("0,000"); return format(x); }
                }
              }
        },
        grid: {
            x: {
                show: false
            },
            y: {
                show: true
            }
        }
    });
}