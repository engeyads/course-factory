<!DOCTYPE html>
<html>
<head>
  <title>HTML Tag Count and SEO Checks</title>
</head>
<body>
  <div>
    <textarea class="textArea" rows="10" cols="50" oninput="analyzeTextArea(this)"></textarea>
    <div class="tagCounts"></div>
    <div class="textToHtmlRatio"></div>
    <div class="seoChecks"></div>
  </div>

  <div>
    <textarea class="textArea" rows="10" cols="50" oninput="analyzeTextArea(this)"></textarea>
    <div class="tagCounts"></div>
    <div class="textToHtmlRatio"></div>
    <div class="seoChecks"></div>
  </div>

  <div>
    <textarea class="textArea" rows="10" cols="50" oninput="analyzeTextArea(this)"></textarea>
    <div class="tagCounts"></div>
    <div class="textToHtmlRatio"></div>
    <div class="seoChecks"></div>
  </div>

  <div id="totalTagCounts"></div>
  <div id="totalTextToHtmlRatio"></div>
  <div id="totalSeoChecks"></div>

  <script>
    window.addEventListener("DOMContentLoaded", function() {
      analyzeAllTextAreas();
    });

    function analyzeTextArea(textArea) {
      var text = textArea.value;
      var pattern = /<([a-zA-Z]+)[^>]*>/g; // Regular expression pattern to match HTML tags
      var tagCount = {};
      var h1Detected = false;

      // Find all HTML tags in the text
      var matches = text.match(pattern);

      // Count the occurrences of each tag
      for (var i = 0; i < matches.length; i++) {
        var tag = matches[i].replace(/[<>]/g, "").toLowerCase(); // Extract the tag name and convert to lowercase

        if (tagCount[tag]) {
          tagCount[tag]++;
        } else {
          tagCount[tag] = 1;
        }

        if (tag === "h1") {
          h1Detected = true;
        }
      }

      // Create a list of tag counts
      var tagList = "<ul>";
      for (var tag in tagCount) {
        tagList += "<li>" + tag + ": " + tagCount[tag] + "</li>";
      }
      tagList += "</ul>";

      // Display the tag counts
      var tagCountsDiv = textArea.parentNode.getElementsByClassName("tagCounts")[0];
      tagCountsDiv.innerHTML = tagList;

      // Check if <h1> tag is detected
      if (h1Detected) {
        alert("The <h1> tag has been detected in this textarea.");
      }

      calculateTextToHtmlRatio(textArea);
      analyzeSeo(textArea);
      analyzeAllTextAreas();
    }

    function calculateTextToHtmlRatio(textArea) {
      var text = textArea.value;
      var totalLength = text.length;
      var htmlLength = text.replace(/<[^>]+>/g, "").length; // Remove HTML tags and calculate remaining length

      var ratio = htmlLength === 0 ? 0 : (totalLength - htmlLength) / htmlLength;

      var textToHtmlRatioDiv = textArea.parentNode.getElementsByClassName("textToHtmlRatio")[0];
      textToHtmlRatioDiv.textContent = "Text-to-HTML Ratio: " + ratio.toFixed(2);
    }

    function analyzeSeo(textArea) {
      var text = textArea.value;
      var wordCount = text.trim().split(/\s+/).length; // Count the number of words

      var seoChecksDiv = textArea.parentNode.getElementsByClassName("seoChecks")[0];
      var seoResults = "";

      if (wordCount === 0) {
        seoResults += "No content found. Add some text for better SEO.";
      } else if (wordCount < 300) {
        seoResults += "Content is too short. Aim for at least 300 words for better SEO.";
      } else {
        seoResults += "Content length is optimal for SEO.";
      }

      seoChecksDiv.textContent = seoResults;
    }

    function analyzeAllTextAreas() {
      var textAreas = document.getElementsByClassName("textArea");
      var totalCounts = {};
      var totalTextLength = 0;
      var totalHtmlLength = 0;
      var totalWordCount = 0;

      // Iterate through all text areas
      for (var i = 0; i < textAreas.length; i++) {
        var textArea = textAreas[i];
        var text = textArea.value;
        var pattern = /<([a-zA-Z]+)[^>]*>/g; // Regular expression pattern to match HTML tags
        var tagCount = {};

        // Find all HTML tags in the text
        var matches = text.match(pattern);

        // Count the occurrences of each tag
        for (var j = 0; j < matches.length; j++) {
          var tag = matches[j].replace(/[<>]/g, "").toLowerCase(); // Extract the tag name and convert to lowercase

          if (tagCount[tag]) {
            tagCount[tag]++;
          } else {
            tagCount[tag] = 1;
          }

          if (totalCounts[tag]) {
            totalCounts[tag]++;
          } else {
            totalCounts[tag] = 1;
          }
        }

        // Calculate text and HTML lengths for text-to-HTML ratio
        var totalLength = text.length;
        var htmlLength = text.replace(/<[^>]+>/g, "").length; // Remove HTML tags and calculate remaining length

        totalTextLength += totalLength;
        totalHtmlLength += htmlLength;

        // Calculate word count for SEO check
        var wordCount = text.trim().split(/\s+/).length;
        totalWordCount += wordCount;

        // Display tag counts
        var tagCountsDiv = textArea.parentNode.getElementsByClassName("tagCounts")[0];
        var tagList = "<ul>";
        for (var tag in tagCount) {
          tagList += "<li>" + tag + ": " + tagCount[tag] + "</li>";
        }
        tagList += "</ul>";
        tagCountsDiv.innerHTML = tagList;

        // Check if <h1> tag is detected
        var h1Detected = tagCount.hasOwnProperty("h1") && tagCount["h1"] > 0;
        if (h1Detected) {
          alert("The <h1> tag has been detected in textarea " + (i + 1) + ".");
        }

        // Display text-to-HTML ratio
        var textToHtmlRatioDiv = textArea.parentNode.getElementsByClassName("textToHtmlRatio")[0];
        var ratio = htmlLength === 0 ? 0 : (totalLength - htmlLength) / htmlLength;
        textToHtmlRatioDiv.textContent = "Text-to-HTML Ratio: " + ratio.toFixed(2);

        // Display SEO check results
        var seoChecksDiv = textArea.parentNode.getElementsByClassName("seoChecks")[0];
        var seoResults = "";
        if (wordCount === 0) {
          seoResults += "No content found. Add some text for better SEO.";
        } else if (wordCount < 300) {
          seoResults += "Content is too short. Aim for at least 300 words for better SEO.";
        } else {
          seoResults += "Content length is optimal for SEO.";
        }
        seoChecksDiv.textContent = seoResults;
      }

      // Display total tag counts
      var totalTagCountsDiv = document.getElementById("totalTagCounts");
      var totalTagList = "<ul>";
      for (var tag in totalCounts) {
        totalTagList += "<li>" + tag + ": " + totalCounts[tag] + "</li>";
      }
      totalTagList += "</ul>";
      totalTagCountsDiv.innerHTML = "<h3>Total Tag Counts</h3>" + totalTagList;

      // Calculate and display total text-to-HTML ratio
      var totalTextToHtmlRatioDiv = document.getElementById("totalTextToHtmlRatio");
      var totalRatio = totalHtmlLength === 0 ? 0 : (totalTextLength - totalHtmlLength) / totalHtmlLength;
      totalTextToHtmlRatioDiv.innerHTML = "<h3>Total Text-to-HTML Ratio</h3>Ratio: " + totalRatio.toFixed(2);

      // Calculate and display total SEO check results
      var totalSeoChecksDiv = document.getElementById("totalSeoChecks");
      var totalSeoResults = "";
      if (totalWordCount === 0) {
        totalSeoResults += "No content found in all text areas. Add some text for better SEO.";
      } else if (totalWordCount < 300) {
        totalSeoResults += "Total content is too short. Aim for at least 300 words for better SEO.";
      } else {
        totalSeoResults += "Total content length is optimal for SEO.";
      }
      totalSeoChecksDiv.innerHTML = "<h3>Total SEO Checks</h3>" + totalSeoResults;
    }
  </script>
</body>
</html>
