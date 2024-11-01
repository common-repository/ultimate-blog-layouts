const attributes= {
	excerptLength: {
		type: 'number',
		default: 25
	},
	showExcerpt: {
		type: 'boolean',
		default: true
	},
	category: {
		type: 'string',
	},
	order: {
		type: 'string',
		default: 'desc'
	},
	orderBy: {
		type: 'string',
		default: 'date'
	},
	perPage: {
		type: 'number',
		default: 3
	},
	showText: {
		type: 'boolean',
		default: true
	},
	text: {
		type: 'string',
		default: 'Read More'
	},
	showAuth: {
		type: 'boolean',
		default: true
	},
	showDate: {
		type: 'boolean',
		default: true
	},
	showTerms: {
		type: 'boolean',
		default: true
	},
	showTags: {
		type: 'boolean',
		default: true
	},
	layouts: {
		type: 'string',
		default: 'layout-1'
	},
	imageSize: {
		type: 'string',
		default: 'large'

	},
	showImage: {
		type: 'boolean',
		default: true
	}
	
};

export default attributes