$(function () {
    // Initialize Parse with your Parse application javascript keys

	var parts = window.location.host.split('.')
	var subdomain = parts[0];
    var Environment = subdomain;
    switch (Environment) {
        case "dev":
            Parse.initialize("FvEPBedzfhHchdviDbKOayUm2W9aPAzCrVcIRTT4", "nPgVAfkwjmaSkDqLTjhEzlJjfHH3QjTJPAZOz8JJ");
            break;
        case "qa":
            Parse.initialize("ZfzVtSyJdEemMVz7RVC5DnWNxrvrcJiFSpopUxHg", "sPHpBrIKIUxbI6e0ItAQC7Qx1F4EmjVeXCw4chM1");
            break;
		case "demo":
            alert("Demo Not Ready");
            break;
		case "beta":
            alert("Beta Not Ready");
            break;
        default:
            Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX", "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67");
    }

	emailVerified();

	function emailVerified() {
        var parseClass = "email_address";
        var parseClassObject = Parse.Object.extend(parseClass);
        var query = new Parse.Query(parseClassObject);
        query.equalTo("email_address_id", window.objectId);
		query.first({
			success: function (object) {
				object.set("is_verified", true);
				object.save();
				$('.loader').hide();
				$('.content').show();
				$('.verified').show();
				$('.email_address').html(object.get("email_address"));
			},
            error: function (error) {
                alert("Error: " + error.code + " " + error.message);
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
				}
				$('.loader').hide();
				$('.content').show();
				$('.not_verified').show();
			}
		});
	}
});