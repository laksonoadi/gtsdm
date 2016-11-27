<?php

/**
 * GT_Graph
 * 
 * Class used for creating graph
 * 
 * @author Agung Puji Mustofa, agungpm_ti -at- yahoo.com
 * @package general
 * @version $Id: GT_Graph.class.php 0001 2012-10-06 12:22:00 $
 */
 
class GT_Graph {
    public $source = 'hichart';
    public $title  = '';
    public $subtitle = '';
    public $show_legend = true;
    public $legend_position = 'top right';
    public $show_value = true;
    public $width = 500;
    public $height = 400;
    public $minimum_y = null;
    public $maximum_y = null;
    public $minimum_x = null;
    public $maximum_x = null;
    public $format_number = null; 
    public $color_list = array (
        '#3399FF',  // red
        '#FFFF66',  //blue
        '#FF9966',  // orange
        '#33CC00', // green
        '#CC9966', // brown
        '#990000', // heavy brown
        '#003399', // heavy blue
        '#D4A017', // gold
        '#347C17', // dark green
        '#7D0552', // maroon,
        '#E238EC', // magenta
        '#6C2DC7', // purple
        '#D16587', // violet
        '#43BFC7', // turqoise,
        '#717D7D', //light cyan
        '#4E8975', // sea green
    );
    public $axis_name = '';
    public $ordinat_name = '';
    public $is_script_loaded = false;
    public $container;
    public $axis_value;
    public $ordinat_value;
    public $margin_bottom = 25;
    public $title_position = -20;
    public $label_name = '';
    public $rotation = 0;
    public $decimal_position = '2';
    public $decimal_point = ',';
    public $decimal_thousand = '.';
    
    /**
     * __construct(): constructor function
     *
     */
     
    function __construct()
    {
        // no nothings
    }
    
    /**
     * createLine(): function to create line graph
     *
     * @param  array $data, its array based multidimensional array
     * @return string, string script
     */
     
    function createLine() {
        $string  = '';
        $chart_name = 'line_chart_'.rand();
        if ($this->source=='hichart') {
            $axis_string = join("','",$this->axis_value);
            $series_string = '';
            if (!empty($this->ordinat_value)) {
                $ordinat_size = sizeOf($this->ordinat_value);
                $ordinat_start = 1;
                foreach ($this->ordinat_value as $ordinat_id => $ordinat) {
                    if ($ordinat_start == $ordinat_size) {
                         $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]}";
                    } else {
                        $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]},";
                    }
                    $ordinat_start++;
                    
                }
            }
            
            if ($this->show_legend== true) {
                $margin_right = 130;
                $legend_enabled = 'true';
            } else {
                $margin_right = 0;
                $legend_enabled = 'false';
            }
            

            
            $string .= '<script language="javascript">'."\n".'
                    $(document).ready(function() {';
            $string .= 'var '.$chart_name.';'."\n";
            $color = join("','",$this->color_list);
        
            $string .= 'chart = new Highcharts.Chart({'."\n";
            
            $string .= "
                chart: {
                    renderTo: '$chart_name',
                    type: 'line',
                    marginRight: $margin_right,
                    marginBottom: $this->margin_bottom
                },
                
                colors : ['$color'],
                title: {
                    text: '$this->title',
                    x: $this->title_position//center
                },
                subtitle: {
                    text: '$this->subtitle',
                    x: $this->title_position
                },
                xAxis: {
                    categories: ['$axis_string'],
                    labels: {
                        rotation: $this->rotation,
                        align: 'right'
                    }
                },
                yAxis: {
                    title: {
                        text: '$this->ordinat_name'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                    
                },
                tooltip: {
    				enabled: false,
                    formatter: function() {
                            return '';
                    }
                },
    			plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                legend: {
                    enabled : $legend_enabled,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 30,
                    borderWidth: 0
                },
                series: [$series_string]
            ";
            
            $string .= ' });'."\n";
            $string .= '});'."\n".'</script>';
            $string .='<div id="'.$chart_name.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"></div>';
        }
        
        
        
        
        return $string;
    }
    
    /**
     * createBar(): function to create bar graph
     *
     * @param  array $data, its array based multidimensional array
     * @return string, string script
     */
     
    function createBar() {
        $string  = '';
        $chart_name = 'bar_chart_'.rand();
        if ($this->source=='hichart') {
            $axis_string = join("','",$this->axis_value);
            $series_string = '';
            if (!empty($this->ordinat_value)) {
                $ordinat_size = sizeOf($this->ordinat_value);
                $ordinat_start = 1;
                foreach ($this->ordinat_value as $ordinat_id => $ordinat) {
                    if ($ordinat_start == $ordinat_size) {
                         $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]}";
                    } else {
                        $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]},";
                    }
                    $ordinat_start++;
                    
                }
            }
            
            if ($this->show_legend== true) {
                $margin_right = 130;
                $legend_enabled = 'true';
            } else {
                $margin_right = 0;
                $legend_enabled = 'false';
            }
            

            
            $string .= '<script language="javascript">'."\n".'
                    $(document).ready(function() {';
            $string .= 'var '.$chart_name.';'."\n";
            $color = join("','",$this->color_list);
        
            $string .= 'chart = new Highcharts.Chart({'."\n";
            
            $string .= "
                chart: {
                    renderTo: '$chart_name',
                    type: 'bar',
                    marginRight: $margin_right,
                    marginBottom: $this->margin_bottom
                },
                
                colors : ['$color'],
                title: {
                    text: '$this->title',
                    x: $this->title_position//center
                },
                subtitle: {
                    text: '$this->subtitle',
                    x: $this->title_position
                },
                xAxis: {
                    categories: ['$axis_string'],
                    labels: {
                        rotation: $this->rotation,
                        align: 'right'
                    }
                },
                yAxis: {
                    title: {
                        text: '$this->ordinat_name'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                    
                },
                tooltip: {
    				enabled: false,
                    formatter: function() {
                            return '';
                    }
                },
    			plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                legend: {
                    enabled : $legend_enabled,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 30,
                    borderWidth: 0
                },
                series: [$series_string]
            ";
            
            $string .= ' });'."\n";
            $string .= '});'."\n".'</script>';
            $string .='<div id="'.$chart_name.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"></div>';
        }
        
        
        
        
        return $string;
    }
    
   /**
     * creatColumn(): function to create column graph
     *
     * @param  array $data, its array based multidimensional array
     * @return string, string script
     */
     
    function createColumn() {
        $string  = '';
        $chart_name = 'column_chart_'.rand();
        if ($this->source=='hichart') {
            $axis_string = join("','",$this->axis_value);
            $series_string = '';
            
            if (isset($this->series_width)) {
                $width_string = ', pointWidth: '.$this->series_width;
            } else {
                $width_string = '';
            }
            if (!empty($this->ordinat_value)) {
                $ordinat_size = sizeOf($this->ordinat_value);
                $ordinat_start = 1;
                foreach ($this->ordinat_value as $ordinat_id => $ordinat) {
                    if ($ordinat_start == $ordinat_size) {
                         $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]}$width_string";
                    } else {
                        $series_string .= "{name : '$ordinat_id', data : [".join(',',$ordinat)."]},";
                    }
                    $ordinat_start++;
                    
                }
            }
        
            
            if ($this->show_legend== true) {
                $margin_right = 130;
                $legend_enabled = 'true';
            } else {
                $margin_right = 0;
                $legend_enabled = 'false';
            }
            

            
            $string .= '<script language="javascript">'."\n".'
                    $(document).ready(function() {';
            $string .= 'var '.$chart_name.';'."\n";
            $color = join("','",$this->color_list);
        
            $string .= 'chart = new Highcharts.Chart({'."\n";
            
            $string .= "
                chart: {
                    renderTo: '$chart_name',
                    type: 'column',
                    marginRight: $margin_right,
                    marginBottom: $this->margin_bottom
                },
                colors : ['$color'],
                title: {
                    text: '$this->title',
                    x: $this->title_position//center
                },
                subtitle: {
                    text: '$this->subtitle',
                    x: $this->title_position
                },
                xAxis: {
                    categories: ['$axis_string'],
                    labels: {
                        rotation: $this->rotation,
                        align: 'right'
                    }
                },
                yAxis: {
                    title: {
                        text: '$this->ordinat_name'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }],
                    labels: {
                        formatter: function() {
                            return Highcharts.numberFormat(this.value, $this->decimal_position, '$this->decimal_point','$this->decimal_thousand');
                        }
                    } 
                },
                tooltip: {
    				enabled: false,
                    formatter: function() {
                            return '';
                    }
                },
    			plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return Highcharts.numberFormat(this.y, $this->decimal_position, '$this->decimal_point','$this->decimal_thousand');
                            }
                        },
                        enableMouseTracking: false
                    }
                },
                legend: {
                    enabled : $legend_enabled,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 30,
                    borderWidth: 0
                },
                series: [$series_string]
            ";
            
            $string .= ' });'."\n";
            $string .= '});'."\n".'</script>';
            $string .='<div id="'.$chart_name.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"></div>';
        }
        
        
        
        
        return $string;
    }
    
  /**
     * creatPie(): function to create pie graph
     *
     * @param  array $data, its array based multidimensional array
     * @return string, string script
     */
     
    function createPie() {
        $string  = '';
        $chart_name = 'pie_chart_'.rand();
        if ($this->source=='hichart') {
            $axis_string = join("','",$this->axis_value);
            $series_string = "{ type: 'pie',name: '$this->label_name', data : [";
            if (!empty($this->ordinat_value)) {
                $ordinat_size = sizeOf($this->ordinat_value);
                $ordinat_start = 1;
                foreach ($this->ordinat_value as $ordinat_id => $ordinat) {
                    if ($ordinat_start == $ordinat_size) {
                         $series_string .= "['".$this->axis_value[$ordinat_id]."', $ordinat]";
                    } else {
                        $series_string .= "['".$this->axis_value[$ordinat_id]."', $ordinat],";
                    }
                    $ordinat_start++;
                    
                }
            }
            $series_string .=']}';
            
            if ($this->show_legend== true) {
                $margin_right = 130;
                $legend_enabled = 'showInLegend: true';
            } else {
                $margin_right = 0;
                $legend_enabled = 'showInLegend: false';
            }
            

            
            $string .= '<script language="javascript">'."\n".'
                    $(document).ready(function() {';
            $string .= 'var '.$chart_name.';'."\n";
            $color = join("','",$this->color_list);
        
            $string .= 'chart = new Highcharts.Chart({'."\n";
            
            $string .= "
                chart: {
                    renderTo: '$chart_name',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    marginRight : 200
                },
                colors : ['$color'],
                title: {
                    text: '$this->title',
                    x: $this->title_position//center
                },
                subtitle: {
                    text: '$this->subtitle',
                    x: $this->title_position
                },
                tooltip: {
    				enabled: true,
					percentageDecimals: 2,
                    formatter: function() {
                            return this.point.name + ': '+ Highcharts.numberFormat(this.percentage, $this->decimal_position, '$this->decimal_point','$this->decimal_thousand') +' %';
                    }
					
                },
                legend : {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    floating: true,
                    backgroundColor: '#FFFFFF',
                    width: 200
                },
    			plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return this.point.name + ': '+ Highcharts.numberFormat(this.percentage, $this->decimal_position, '$this->decimal_point','$this->decimal_thousand') +' %';
                            }
                        },
                        enableMouseTracking: false,
                        $legend_enabled
                    }
                },
                series: [$series_string]
            ";
            
            $string .= ' });'."\n";
            $string .= '});'."\n".'</script>';
            $string .='<div id="'.$chart_name.'" style="width:'.$this->width.'px;height:'.$this->height.'px;"></div>';
        }
        
        
        
        
        return $string;
    }
    
    
    
    
}

?>