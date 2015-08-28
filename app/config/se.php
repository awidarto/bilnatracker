<?php



return array(
    'adminroles'=> array(
        'root'=>'Root User',
        'superuser'=>'Superuser'
    ),
	'currency'=>array(
			'IDR'=>'IDR',
			'USD'=>'USD',
			'SGD'=>'SGD',
			'AUD'=>'AUD',
			'EURO'=>'EURO',
			'GBP'=>'GBP'
		),

    'office_categories'=>array(
            'Office'=>'Office',
            'Showroom'=>'Showroom'
        ),

    'search_office_categories'=>array(
            ''=>'All',
            'Office'=>'Office',
            'Showroom'=>'Showroom'
        ),

    'agent_categories'=>array(
            'Distributor'=>'Distributor',
            'Agent'=>'Agent'
        ),
    'search_agent_categories'=>array(
            ''=>'All',
            'Distributor'=>'Distributor',
            'Agent'=>'Agent'
        ),

    'company_categories'=>array(
            'Manufacturer'=>'Manufacturer',
            'Local Distributor'=>'Local Distributor',
            'Local Agent'=>'Local Agent'
        ),
    'search_company_categories'=>array(
            ''=>'All',
            'Manufacturer'=>'Manufacturer',
            'Local Distributor'=>'Local Distributor',
            'Local Agent'=>'Local Agent'
        ),

	'product_categories'=>array(
			'Finishes'=>'Finishes',
			'Finishes Natural Stone or Timber'=>'Finishes (Natural Stone or Timber)',
			'Structure'=>'Structure',
			'Furniture'=>'Furniture'
		),
	'search_product_categories'=>array(
			''=>'All',
			'Finishes'=>'Finishes',
            'Finishes Natural Stone or Timber'=>'Finishes (Natural Stone or Timber)',
			'Structure'=>'Structure',
			'Furniture'=>'Furniture'
		),

	'main_categories'=>array(
            'Finishes'=>'Finishes',
            'Finishes Natural Stone or Timber'=>'Finishes (Natural Stone or Timber)',
            'Structure'=>'Structure',
            'Furniture'=>'Furniture'
		),

	'search_main_categories'=>array(
            ''=>'All',
            'Finishes'=>'Finishes',
            'Finishes Natural Stone or Timber'=>'Finishes (Natural Stone or Timber)',
            'Structure'=>'Structure',
            'Furniture'=>'Furniture'
		),

	'cartstatus'=>array(
			'open'=>'Regular',
			'checkedout'=>'Checked Out',
			'pending'=>'Pending Payment',
			'paid'=> 'Paid',
			'cancelled'=> 'Cancelled'
		),
	'search_cartstatus'=>array(
			''=>'All',
			'open'=>'Regular',
			'checkedout'=>'Checked Out',
			'pending'=>'Pending Payment',
			'paid'=> 'Paid',
			'cancelled'=> 'Cancelled'
		),
	'applications'=>array(
		'Roof'=>array('value'=>'Roof'),
		'Ceiling'=>array('value'=>'Ceiling'),
		'Wall'=>array('value'=>'Wall'),
		'Floor'=>array('value'=>'Floor'),
		'Carpentry'=>array('value'=>'Carpentry'),
		'Upholstery'=>array('value'=>'Upholstery'),
		'Exterior'=>array('value'=>'Exterior'),
		'Interior'=>array('value'=>'Interior'),
		'Wet Area'=>array('value'=>'Wet Area')
	),
	'systems'=>array(
		'Roof'=>array('value'=>'Roof'),
		'Ceiling'=>array('value'=>'Ceiling'),
		'Wall'=>array('value'=>'Wall'),
		'Floor'=>array('value'=>'Floor'),
		'Doors & Windows'=>array('value'=>'Doors & Windows'),
		'Vertical Access'=>array('value'=>'Vertical Access'),
		'Hard Landscape'=>array('value'=>'Hard Landscape')
	),
	'functions'=>array(
		'Seating'=>array('value'=>'Floor'),
		'Beds'=>array('value'=>'Beds'),
		'Tables'=>array('value'=>'Tables'),
		'Lighting'=>array('value'=>'Lighting'),
		'Taps & Sinks'=>array('value'=>'Taps & Sinks'),
		'Hardware & Equipment'=>array('value'=>'Hardware & Equipment'),
		'Privacy & Sunshading'=>array('value'=>'Privacy & Sunshading'),
		'Display & Storage'=>array('value'=>'Display & Storage'),
		'Gates & Grilles'=>array('value'=>'Gates & Grilles'),
		'Pools & Tubs'=>array('value'=>'Pools & Tubs'),
		'Play Structures'=>array('value'=>'Play Structures')

	),
    'search_project_applications'=>array(
        ''=>'All',
        'Residential'=> 'Residential',
        'Office'=>'Office',
        'Laboratory'=>'Laboratory',
        'Retail FB'=>'Retail, F&B',
        'Sporting & Recreational'=>'Sporting & Recreational',
        'Entertainment'=>'Entertainment',
        'Hospitality'=>'Hospitality',
        'Healthcare'=>'Healthcare',
        'Educational'=>'Educational',
        'Museum'=>'Museum',
        'Transportation'=>'Transportation',
        'Industrial'=>'Industrial',
        'Historic Restoration'=>'Historic Restoration'
    ),

    'project_applications'=>array(
        'Residential'=> 'Residential',
        'Office'=>'Office',
        'Laboratory'=>'Laboratory',
        'Retail FB'=>'Retail, F&B',
        'Sporting & Recreational'=>'Sporting & Recreational',
        'Entertainment'=>'Entertainment',
        'Hospitality'=>'Hospitality',
        'Healthcare'=>'Healthcare',
        'Educational'=>'Educational',
        'Museum'=>'Museum',
        'Transportation'=>'Transportation',
        'Industrial'=>'Industrial',
        'Historic Restoration'=>'Historic Restoration'
    ),
	'picsizes'=>array(
		array('prefix'=>'sm_','w'=>50,'h'=>90,'opt'=>'fit','ext'=>'.jpg','q'=>90),
		array('prefix'=>'med_','w'=>333,'h'=>324,'opt'=>'fit','ext'=>'.jpg','q'=>90),
		array('prefix'=>'lar_','w'=>650,'h'=>610,'opt'=>'fit','ext'=>'.jpg','q'=>90),
		array('prefix'=>'lar_sq_','w'=>500,'h'=>500,'opt'=>'fit','ext'=>'.jpg','q'=>90),
		array('prefix'=>'lar_port_','w'=>500,'h'=>650,'opt'=>'portrait','ext'=>'.jpg','q'=>90),
	),

	'auction_run'=>array(
		'manual'=>'Manual',
		'auto'=>'Auto'
	),
	'admin_email'=>'admin@peachtoblack.com',
	'admin_name'=>'Shopkeeper@PeachtoBlack'




);