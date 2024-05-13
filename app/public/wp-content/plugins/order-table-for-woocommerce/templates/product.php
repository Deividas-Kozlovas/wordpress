<script type="text/template" id="productTemplate">
<%



if (Array.isArray(otfw.product_list))  {

	var i;

for (i = 0; i < otfw.product_list.length; i++){



	if (otfw.product_list[i]  == "image_pre"){	%>
	<td data-label="" class="otfw_thumb" >
<div class="otfw_image_<%= otfw.image_size%>">
		<%= image_pre[0].src %>
		</div>
	</td>
<%
continue;
}

	else if (otfw.product_list[i] == "quantity_input_pre"){
		if(otfw.confirm_input == "yes"){          %>
			<td data-label="Quantity" class="otfw_form otfw_centered">
			<div class="flex-container">
			<div class="otfw-cart-action-2" style="align-self: center"><i  class="fas fa-check fa-lg"></i></div>
			<form  id='form<%= id %>' style="align-self: center" class="cart" method="post" enctype='multipart/form-data'>
			<%= quantity_input_pre %>
			</form>

			</div>
			<% } else { %>
			<td data-label="Quantity" class="otfw_form otfw_centered">
			<form id='<%= id %>' style="align-self: center" class="cart" method="post" enctype='multipart/form-data'>
			<%= quantity_input_pre %></p>
			</form>

			<% } %>
			</td>

			<%
			continue;
			}
	else if (otfw.product_list[i]  == "title_pre"){

			%>
			<td data-label="" class="otfw_desc otfw_title_desc" >

			<% if ( otfw.link_title == 'yes' && otfw.sale_badge == 'yes') {

				 if(typeof on_sale_pre !== 'undefined' && on_sale_pre) { %>
				<p class="otfw_title_para" >
				<a class="otfw_title" href="<%= permalink_pre %>" title="<%= title_pre %>"> <%= title_pre %> <span class="otfw_badge otfw_badge_sale oval">

				<%= otfw.sale_string %></span></a></p>

			<% } else { %>
			<p class="otfw_title_para" >
			<a class="otfw_title" href="<%= permalink_pre %>" title="<%= title_pre %>"> <%= title_pre %></a>
			</p>
			<% }
			 }
	else if (otfw.link_title == 'yes' && otfw.sale_badge == 'no'){ %>
			<p class="otfw_title_para" >
			<a class="otfw_title" href="<%= permalink_pre %>" title="<%= title_pre %>"> <%= title_pre %></a>
			</p>
			<% }
	else if (otfw.link_title == 'no' && otfw.sale_badge == 'yes') { %>

				<% if(on_sale_pre){  %>

				<p class="otfw_title_para">  <%= title_pre %> <span class="otfw_badge otfw_badge_sale oval"><%= otfw.sale_string %></span></p>
			<% } else { %>

				<p class="otfw_title_para">  <%= title_pre %> </p>
				<% }
				 }  else { %>

				<p class="otfw_title_para">  <%= title_pre %> </p>

				<% } %>
	</td>

 <%
 continue;
 }



%>



<%
switch(i+1){

case 1:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr1 %></p>


<% break;

case 2:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr2 %></p>


<% break;
case 3:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr3 %></p>


<% break;
case 4:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr4 %></p>


<% break;

case 5:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr5 %></p>


<% break;
case 6:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr6 %></p>


<% break;
case 7:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr7 %></p>


<% break;
case 8:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr8 %></p>


<% break;
case 9:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr9 %></p>


<% break;
case 10:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr10 %></p>


<% break;
case 11:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr11 %></p>


<% break;
case 12:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr12 %></p>


<% break;
case 13:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr13 %></p>


<% break;
case 14:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr14 %></p>


<% break;
case 15:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr15 %></p>


<% break;
case 16:
 %>

<td data-label="" class="otfw_desc" >
<p>  <%= nr16 %></p>


<% break;


 }
%>

 </td> <%
 continue;



  	} }

	%>

</script>
