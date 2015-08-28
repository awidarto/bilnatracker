

		var extra = aData['extra'];

		sOut += '<tr class="irc_pc"></tr>';
		sOut += '<tr><td colspan="3" style="margin-right:15px;"><h4>Company Information</h4></td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td colspan="3"><h4>Invoice Address</h4></td></tr>';
	    sOut += '<tr><td>Company Name </td><td>:</td><td> '+ extra.address+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td>Company Name </td><td>:</td><td> '+ extra.company+'</td></tr>';

		sOut += '<tr><td>Company Address </td><td>:</td><td> '+ extra.address + '<br/>' + extra.city + '<br/>' + extra.zipCode + '<br/>' + extra.state +'</td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td>Company Address</td><td>:</td><td> '+ extra.invoice_address_conv +'</td></tr>';
    	sOut += '<tr><td>Company Phone </td><td>:</td><td> '+ extra.companyphone+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td>Golf Tournament</td><td>:</td>'+rowGolf+'</tr>';
    	sOut += '<tr><td>Company Fax </td><td>:</td><td> '+ extra.companyfax+'</td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td></tr>';
    	sOut += '<tr><td>Industrial Dinner</td><td>:</td>'+rowDinner+' <td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td></tr>';

