/*jslint browser: true, nomen: false, white: false */
/*global $, console */

CAKE = {};

CAKE.tests = (function() {
	var cases = [],
		showPasses = true,
		showCoverage,
		getTestUrl,
		parseResponse,
		displayResults,
		updateResults,
		runAll,
		run,
		results = {},
		init;

	getTestUrl = function(testCase) {
		var url;

		url = 'test.php?output=text&case=' + testCase.replace(/\//g, '%2F');
		
		if (showPasses) {
			url += "&show_passes=1";
		}
		if (showCoverage) {
			url += "&code_coverage=true";
		}

		url += "&ts=" + $.now();
		return url;
	};

	parseResponse = function(data) {
		var re,
		match,
		result = {};

		if (data.match(/OK/)) {
			result.result = 'OK';
		} else if (data.match(/FAIL/)) {
			result.result = 'FAIL';
		} else {
			result.result = 'DUNNO';
		}

		match = data.match(/(\d+)\/(\d+),/);
		if (match) {
			result.tests= {
			total: parseInt(match[1], 10),
			passes: parseInt(match[2], 10)
			};
		}
		match = data.match(/Time: ([\d\.]+)/);
		if (match) {
			result.time = parseFloat(match[1]);
		}

		match = data.match(/memory: ([\d\,]+)/);
		if (match) {
			result.memory = parseInt(match[1].replace(/,/g, ''), 10);
		}

		re = /(\w+): (\d+)/g;
		do {
			match = re.exec(data);
			if (match && !match[1].match(/run|Time|memory/)) {
				result[match[1]] = parseInt(match[2], 10);
			}
		} while(match);

		return result;
	};

	displayResults = function() {
		var div = $('#testResults');

		if (!div.length) {
			div = $('<div id="testResults" />')
				.hide()
				.appendTo('body');
		}

        updateResults();

		div.dialog({
			title: "Summary of all tests ran so far",
			width: 800
		});
	};

    updateResults = function() {
		var div = $('#testResults'),
			contents,
			dl,
			testCases = 0,
			testCasePasses = 0,
			testMethods = 0,
			testMethodPasses = 0,
			assertions = 0,
			passes = 0,
			fails = 0,
			exceptions = 0,
			totalTime = 0,
			minMemory = Infinity,
			maxMemory = 0,
			minTime = Infinity,
			maxTime = 0,
			minMemoryTest,
			maxMemoryTest,
			minTimeTest,
			maxTimeTest;

		$.each(results, function(name) {
			testCases += 1;

			if (this.result === 'OK') {
				testCasePasses += 1;
			}

			if (this.tests) {
				testMethods += this.tests.total;
				testMethodPasses += this.tests.passes;
			}
		
			if (this.Passes) {	
				assertions += this.Passes;
				passes += this.Passes;
			}

			if (this.Failures) {	
				assertions += this.Failures;
				fails += this.Failures;
			}

			if (this.memory) {
				if (this.memory > maxMemory) {
					maxMemory = this.memory;
					maxMemoryTest = name;
				}
				if (this.memory < minMemory) {
					minMemory = this.memory;
					minMemoryTest = name;
				}
			}

			if (this.time) {
				totalTime += this.time;

				if (this.time > maxTime) {
					maxTime = this.time;
					maxTimeTest = name;
				}
				if (this.time < minTime) {
					minTime = this.time;
					minTimeTest = name;
				}
			}
		});

		if (!div.length) {
			div = $('<div id="testResults" />')
				.hide()
				.appendTo('body');
		}


		table = $('<table style="width:100%" />')
			.append('<tr><th rowspan=2 style="width:20%" >&nbsp;</th><th colspan=3 >Results</th></tr>')
			.append('<tr><th>Total</th><th>Pass</th><th>Fail</th></tr>')
			.append('<tr><th>Cases</th><td>' + testCases + '</td><td>' + testCasePasses + '</td><td>' + (testCases - testCasePasses) + '</td></tr>')
			.append('<tr><th>Methods</th><td>' + testMethods + '</td><td>' + testMethodPasses + '</td><td>' + (testCases - testCasePasses) + '</td></tr>')
			.append('<tr><th>Assertions</th><td>' + assertions + '</td><td>' + passes + '</td><td>' + fails + '</td></tr>');

		dl = $('<dl style="width:100%" />')
			.append('<dt>Exceptions</dt>')
			.append('<dd>' + exceptions + '</dd>')
			.append('<dt>Total test time</dt>')
			.append('<dd>' + totalTime.toFixed(2) + 's</dd>')
			.append('<dt>Fastest Test</dt>')
			.append('<dd>' + minTimeTest + ' (' + minTime.toFixed(2) + ' s)</dd>')
			.append('<dt>Slowest Test</dt>')
			.append('<dd>' + maxTimeTest + ' (' + maxTime.toFixed(2) + ' s)</dd>')
			.append('<dt>Lowest Memory</dt>')
			.append('<dd>' + minMemoryTest + ' (' + (minMemory / 1048576).toFixed(2) + ' MB)</dd>')
			.append('<dt>Most Memory</dt>')
			.append('<dd>' + maxMemoryTest + ' (' + (maxMemory / 1048576).toFixed(2) + ' MB)</dd>')

		dl.prepend(table);

		div.html(dl);			
    };

	runAll = function(cb) {
		var i = 0, runNextTest;

		results = {};
		displayResults();

		runNextTest = function() {
			if (cases[i]) {
				run(cases[i], runNextTest);
				i += 1;
			} else {
				$('#testResults').prev().find('span.ui-dialog-title')
					.html('All tests complete');

                if (cb && typeof cb === 'function') {
                    cb();
                }
			}
		};

		runNextTest();
	};

	run = function(testCase, cb) {
		var container,
			details,
			href,
			pre,
			start;

        if (testCase === 'all') {
            return runAll(cb);
        }

		container = $('#' + testCase.replace(/\//g, ''));
		href = getTestUrl(testCase);

		if (!container.find('a.showDetails').length) {
			details = $('<a class="showDetails">[ details ]</a>');
			details.click(function() {
				container.find('pre').toggle();
				return false;
			});
			container.append(details);
		}

		pre = container.find('pre');
		if (!pre.length) {
			pre = $('<pre />').hide();
			pre.appendTo(container);
		}
		pre.html('test running...');

		container
			.removeClass('pass')
			.removeClass('fail')
			.removeClass('unknown')
			.addClass('running');

		results[testCase] = {};

		start = $.now();

		$.ajax({
			url: href, 
			success: function(data, textStatus, jqXHR) {
				var result = parseResponse(data);

				container
					.removeClass('running')
					.data(result);

				results[testCase] = result;
                updateResults();

				if (result.result === 'OK') {
					container.addClass('pass');
				} else if (result.result === 'FAIL') {
					container.addClass('fail');
				} else {
					container.addClass('unknown');
				}

				pre.html(data.replace(/</g,'&lt;'));
				if (console && console.log) {
					console.log(data);
				}

				if (cb) {
					cb();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				container
                    .removeClass('running')
				    .addClass('fail');

				results[testCase] = {
					result: 'error',
					time: ($.now() - start) / 1000
				};
                updateResults();

				pre.html("test failed to return anything");

                if (cb && typeof cb === 'function') {
					cb();
				}
			}
		});
	};

	init = function() {
		var ul, li, links;

		links = $('div.test-results ul li a');

		if (!links.length) {
			return;
		}

		ul = $('<ul class="floatingMenu" />');

        li = $('<li />').appendTo(ul);
		$('<a id="showResults">Show Results</a>')
			.click(displayResults)
            .appendTo(li);

		li = $('<li />').appendTo(ul);
		$('<a id="runAll">Run All Tests</a>')
			.click(runAll)
            .appendTo(li);

        ul.appendTo('body');

		links.each(function(){
			var href = $(this).attr('href'),
			testCase;

			testCase = href.match(/case=([a-zA-Z0-9%]+)/);
			if (!testCase) {
				return;
			}

			testCase = testCase[1].replace(/%2F/g, '/');

			cases.push(testCase);
			$(this).parent().attr('id', testCase.replace(/\//g, ''));

			$(this).click(function() {
				run(testCase);
				return false;
			});
		});
	};

	return {
		init: init,
		results: displayResults,
		run: run
	};
}());

CAKE.tests.init();
