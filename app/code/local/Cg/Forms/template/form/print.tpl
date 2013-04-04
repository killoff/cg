<h1>{$customer.firstname} {$customer.middlename} {$customer.lastname} ({$customer.dob|date_format:"%d.%m.%Y"})</h1>
<h2>{$employee.firstname} {$employee.lastname}</h2>
<p>{$data.visit_1}</p>
<p>{$data.visit_2}</p>
{$data.user_date|date_format:"%d.%m.%Y"}

<script type="text/javascript">
window.onload=function(){
    window.print();
};
</script>

