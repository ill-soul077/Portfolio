function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }

// Typewriter effect function
function typewriterEffect() {
    const lines = [
        "Software Developer",
        "Competitive Programmer"
    ];
    
    const line1Element = document.getElementById("typewriter-line1");
    const line2Element = document.getElementById("typewriter-line2");
    const cursor = document.querySelector(".typewriter-cursor");
    
    // Check if elements exist
    if (!line1Element || !line2Element || !cursor) {
        console.log("Typewriter elements not found");
        return;
    }
    
    let currentLineIndex = 0;
    let currentChar = 0;
    let isDeleting = false;
    let typeSpeed = 80; // Faster typing speed
    let deleteSpeed = 40; // Faster deleting speed
    let pauseAfterTyping = 800; // Shorter pause after typing complete word
    let pauseBeforeDeleting = 200; // Very short pause before starting to delete
    let pauseAfterDeleting = 300; // Short pause after deleting
    
    function type() {
        const currentText = lines[currentLineIndex];
        const displayElement = line1Element; // Always use first line for typing/deleting
        
        if (isDeleting) {
            // Deleting characters
            displayElement.textContent = currentText.substring(0, currentChar);
            currentChar--;
            
            if (currentChar < 0) {
                // Finished deleting, move to next line
                isDeleting = false;
                currentLineIndex++;
                currentChar = 0;
                
                if (currentLineIndex < lines.length) {
                    // Start typing next line after short pause
                    setTimeout(type, pauseAfterDeleting);
                } else {
                    // All lines processed, show final result
                    showFinalResult();
                }
                return;
            }
        } else {
            // Typing characters
            displayElement.textContent = currentText.substring(0, currentChar + 1);
            currentChar++;
            
            if (currentChar === currentText.length) {
                // Finished typing current line
                if (currentLineIndex === lines.length - 1) {
                    // Last line typed, show final result after pause
                    setTimeout(showFinalResult, pauseAfterTyping);
                    return;
                } else {
                    // Start deleting after pause
                    setTimeout(() => {
                        isDeleting = true;
                        setTimeout(type, pauseBeforeDeleting);
                    }, pauseAfterTyping);
                    return;
                }
            }
        }
        
        // Continue typing or deleting
        setTimeout(type, isDeleting ? deleteSpeed : typeSpeed);
    }
    
    function showFinalResult() {
        // Clear the typing line and show both lines
        line1Element.textContent = lines[0];
        line2Element.textContent = lines[1];
        
        // Keep cursor visible and blinking
        cursor.style.display = 'inline';
    }
    
    // Start typing effect
    console.log("Starting typewriter effect");
    // Clear both lines initially
    line1Element.textContent = "";
    line2Element.textContent = "";
    type();
}

// Initialize typewriter effect
function initTypewriter() {
    // Wait a bit for DOM to be ready
    setTimeout(typewriterEffect, 500);
}

// Start typewriter effect when page loads
document.addEventListener('DOMContentLoaded', function() {
    typewriterEffect();
});


  // Get the button
let mybutton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      mybutton.style.display = "flex";
    } else {
      mybutton.style.display = "none";
    }
  }
  
  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
  



  
  let sayac=5;

function myStyle() {
    let cssStyle=document.getElementById("cssStyle");
    
    console.log(sayac);
    if(sayac==0){
        sayac=1;
        cssStyle.href="assets/css/style2.css";
        
    }
    else if(sayac==1){
      sayac=2;
      cssStyle.href="assets/css/style3.css";
      
  }
  else if(sayac==2){
    sayac=3;
    cssStyle.href="assets/css/style4.css";
    
}
else if(sayac==3){
  sayac=4;
  cssStyle.href="assets/css/style5.css";
  
}
else if(sayac==4){
  sayac=5;
  cssStyle.href="assets/css/style6.css";
  
}
    else{
        sayac=0;
        cssStyle.href="assets/css/style.css";
        
    }
    
    
  }

  


let skillsHtml=document.getElementsByClassName("skillsHtml")[0];

let worksHtml=document.getElementsByClassName("worksHtml")[0];
let educationHtml=document.getElementsByClassName("educationHtml")[0];
let interestsHtml=document.getElementsByClassName("interestsHtml")[0];
let repositoryHtml=document.getElementsByClassName("repositoryHtml")[0];

let resume="api/portfolio.php?" + new Date().getTime(); // Add timestamp to prevent caching
const data=fetch(resume)
.then(response=> {
    console.log('API Response status:', response.status);
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    return response.text().then(text => {
        console.log('Raw API response:', text);
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('JSON parsing error:', e);
            console.error('Response text:', text);
            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
        }
    });
})
.then(resume=>{
    

    let user = resume.basics;

    

    // Image is set manually in HTML, no need to update from database
    // document.getElementById("myImg").src = "assets/images/" + user.image;
    document.getElementById("name").innerHTML = user.name;

    document.getElementById("home").innerHTML = user.name;
    
    
    // Use typewriter effect instead of setting label directly
    // document.getElementById("label").innerHTML = user.label;
    initTypewriter(); // Start typewriter effect

    document.getElementById("location").innerHTML = user.location.city+", "+user.location.countryCode;
    document.getElementById("email").innerHTML = user.email;
    document.getElementById("email").href="mailto:"+user.email;

    document.getElementById("twitterHtml").href=user.profiles.twitter.url;
    document.getElementById("githubHtml").href=user.profiles.github.url;
    document.getElementById("linkedinHtml").href=user.profiles.linkedin.url;

    document.getElementById("linkedinHtml").innerHTML="HASSAN";
    document.getElementById("githubHtml").innerHTML=""+user.profiles.github.username;
    document.getElementById("twitterHtml").innerHTML="@"+user.profiles.twitter.username;



   

    document.getElementById("summary").innerHTML = user.summary;

    // Skills will be loaded by the loadSkills function below
    if (resume.skills && resume.skills.length > 0) {
        loadSkills(resume.skills);
    } 

//repositoryHtml
let repository=resume.repository;

//<p style="display=inline-block; font-size: 0.7em; font-style: italic;">${repository[i].bestLang}</p>
//

for(let i in repository){

  repositoryHtml.innerHTML +=`<hr>`;
     repositoryHtml.innerHTML +=`<div class="qr-code"><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${repository[i].link}" alt="qr-code"></div>`;
  
  repositoryHtml.innerHTML+=`<h3 style="display:inline-block;">${repository[i].name}</h3>`;
  repositoryHtml.innerHTML+=`<a target="_blank" href="${repository[i].link}"><span class="link" style="display:inline-block;">Go Repository <i class="fa-solid fa-arrow-up-right-from-square"></i></span></a>`;
  repositoryHtml.innerHTML+=`<a target="_blank" href="${repository[i].viewLink}"><span class="link" style="display:inline-block;">View <i class="fa-solid fa-arrow-up-right-from-square"></i></span></a>`;
  repositoryHtml.innerHTML+=`<p style=" font-size: 0.7em; font-style: italic;">${repository[i].bestLang}</p>`;

  repositoryHtml.innerHTML+=`<p>${repository[i].explanation}</p>`;
  for(let j in repository[i].tag){
    repositoryHtml.innerHTML+=`<span style="display:inline-block;">${repository[i].tag[j]}</span>`;
  }
    
  
  

  

}




    

  let works=resume.work;
  for(let i in works){
    let list=works[i];
        
    worksHtml.innerHTML +=`<hr>`;
    worksHtml.innerHTML +=`<h4>${list.position} - ${list.name}</h4>`;
    
    worksHtml.innerHTML +=`<span class="worksSpan">${list.startDate} - ${list.location}</span>`;
    
    
    worksHtml.innerHTML +=`<p>${list.summary}</p>`;

    

}

//educationHtml studyType

let education=resume.education;

for(let i in education){
  let edu = education[i];
  educationHtml.innerHTML +=`<hr>`;
  educationHtml.innerHTML +=`<h3>${edu.institution}</h3>`;
  educationHtml.innerHTML +=`<h4>${edu.area} (${edu.studyType})</h4>`;
  educationHtml.innerHTML +=`<p class="worksSpan">(${edu.startDate}-${edu.endDate})</p>`;
  if(edu.gpa) {
    educationHtml.innerHTML +=`<p><strong>GPA/CGPA:</strong> ${edu.gpa}</p>`;
  }
  if(edu.courses && edu.courses.length > 0) {
    educationHtml.innerHTML +=`<p><strong>Achievements:</strong> ${edu.courses.join(', ')}</p>`;
  }
}



//interestsHtml

let interests=resume.interests;


for(let i in interests){
  interestsHtml.innerHTML +=`<span style="display:inline-block;" class="interestsSpan">${interests[i]}</span>`;

}



   
})
.catch(error => {
    console.error('Portfolio data loading error:', error);
    
    // Fallback to static JSON file
    console.log('Attempting fallback to resume.json...');
    fetch('resume.json?' + new Date().getTime())
    .then(response => response.json())
    .then(resume => {
        console.log('Fallback successful, using static JSON data');
        loadPortfolioData(resume);
    })
    .catch(fallbackError => {
        console.error('Fallback also failed:', fallbackError);
        // Show error message to user
        document.body.innerHTML = `
            <div style="text-align: center; margin-top: 100px; font-family: Arial, sans-serif;">
                <h1 style="color: #e74c3c;">Portfolio Loading Error</h1>
                <p>Unable to load portfolio data. Please check:</p>
                <ul style="display: inline-block; text-align: left;">
                    <li>XAMPP MySQL is running</li>
                    <li>Database is properly configured</li>
                    <li>API files are accessible</li>
                </ul>
                <p style="margin-top: 20px;">
                    <a href="api/portfolio.php" target="_blank" style="color: #3498db;">Test API directly</a> | 
                    <a href="admin_login.php" style="color: #3498db;">Admin Login</a>
                </p>
            </div>
        `;
    });
});

// Function to load portfolio data (extracted for reuse)
function loadPortfolioData(resume) {
    let user = resume.basics;
    
    // Image is set manually in HTML, no need to update from database
    // if (user && user.image) {
    //     // Check if image path already includes assets/images
    //     const imagePath = user.image.includes('assets/images/') ? user.image : user.image;
    //     document.getElementById("myImg").src = imagePath;
    // }
    
    if (user) {
        if (user.name) document.getElementById("name").innerHTML = user.name;
        if (user.name) document.getElementById("home").innerHTML = user.name;
        // Use typewriter effect instead of setting label directly
        // if (user.label) document.getElementById("label").innerHTML = user.label;
        initTypewriter(); // Start typewriter effect
        if (user.location) {
            document.getElementById("location").innerHTML = user.location.city + ", " + user.location.countryCode;
        }
        if (user.email) {
            document.getElementById("email").innerHTML = user.email;
            document.getElementById("email").href = "mailto:" + user.email;
        }
        if (user.summary) document.getElementById("summary").innerHTML = user.summary;
        
        if (user.profiles) {
            if (user.profiles.twitter) {
                document.getElementById("twitterHtml").href = user.profiles.twitter.url;
                document.getElementById("twitterHtml").innerHTML = "@" + user.profiles.twitter.username;
            }
            if (user.profiles.github) {
                document.getElementById("githubHtml").href = user.profiles.github.url;
                document.getElementById("githubHtml").innerHTML = user.profiles.github.username;
            }
            if (user.profiles.linkedin) {
                document.getElementById("linkedinHtml").href = user.profiles.linkedin.url;
                document.getElementById("linkedinHtml").innerHTML = "HASSAN";
            }
        }
    }
    
    // Load other sections
    if (resume.skills && resume.skills.length > 0) {
        loadSkills(resume.skills);
    }
    
    if (resume.repositories && resume.repositories.length > 0) {
        loadProjects(resume.repositories);
    }
    
    if (resume.work && resume.work.length > 0) {
        loadWork(resume.work);
    }
    
    if (resume.education && resume.education.length > 0) {
        loadEducation(resume.education);
    }
}

// Function to load skills
function loadSkills(skills) {
    var skillsContent = "";
    skills.forEach(function(skill) {
        skillsContent += '<div class="skills-item"><h3>' + skill.name + '</h3>';
        
        // Parse keywords if it's a JSON string
        var keywords = [];
        if (typeof skill.keywords === 'string') {
            try {
                keywords = JSON.parse(skill.keywords);
            } catch (e) {
                keywords = skill.keywords.split(',').map(k => k.trim());
            }
        } else {
            keywords = skill.keywords || [];
        }
        
        if (keywords.length > 0) {
            skillsContent += '<div class="skills-tags">';
            keywords.forEach(function(keyword) {
                skillsContent += '<span class="skill-tag">' + keyword + '</span>';
            });
            skillsContent += '</div>';
        }
        skillsContent += '</div>';
    });
    
    // Use the global skillsHtml variable
    if (skillsHtml) {
        skillsHtml.innerHTML = skillsContent;
    }
}

// Function to load projects
function loadProjects(projects) {
    var projectsHtml = "";
    projects.forEach(function(project) {
        projectsHtml += '<div class="project-item">';
        projectsHtml += '<h3>' + project.name + '</h3>';
        if (project.description) {
            projectsHtml += '<p>' + project.description + '</p>';
        }
        
        if (project.url || project.github_url) {
            projectsHtml += '<div class="project-links">';
            if (project.url) {
                projectsHtml += '<a href="' + project.url + '" target="_blank">View Project</a>';
            }
            if (project.github_url) {
                projectsHtml += '<a href="' + project.github_url + '" target="_blank">GitHub</a>';
            }
            projectsHtml += '</div>';
        }
        
        // Parse tags if it's a JSON string
        var tags = [];
        if (typeof project.tags === 'string') {
            try {
                tags = JSON.parse(project.tags);
            } catch (e) {
                tags = project.tags.split(',').map(t => t.trim());
            }
        } else {
            tags = project.tags || [];
        }
        
        if (tags.length > 0) {
            projectsHtml += '<div class="project-tags">';
            tags.forEach(function(tag) {
                projectsHtml += '<span class="project-tag">' + tag + '</span>';
            });
            projectsHtml += '</div>';
        }
        
        projectsHtml += '</div>';
    });
    document.getElementById("projects").innerHTML = projectsHtml;
}

// Function to load work experience
function loadWork(work) {
    var workHtml = "";
    work.forEach(function(job) {
        workHtml += '<div class="work-item">';
        workHtml += '<h3>' + job.position + '</h3>';
        workHtml += '<h4>' + job.company + '</h4>';
        
        if (job.start_date || job.end_date) {
            workHtml += '<div class="work-dates">';
            if (job.start_date) workHtml += job.start_date;
            if (job.start_date && job.end_date) workHtml += ' - ';
            if (job.end_date) workHtml += job.end_date;
            else if (job.start_date) workHtml += ' - Present';
            workHtml += '</div>';
        }
        
        if (job.summary) {
            workHtml += '<p>' + job.summary + '</p>';
        }
        
        workHtml += '</div>';
    });
    document.getElementById("work").innerHTML = workHtml;
}

// Function to load education
function loadEducation(education) {
    var educationHtml = "";
    education.forEach(function(edu) {
        educationHtml += '<div class="education-item">';
        educationHtml += '<h3>' + edu.institution + '</h3>';
        if (edu.area) {
            educationHtml += '<h4>' + edu.area;
            if (edu.studyType) educationHtml += ' (' + edu.studyType + ')';
            educationHtml += '</h4>';
        }
        
        if (edu.startDate || edu.endDate) {
            educationHtml += '<div class="education-dates">';
            if (edu.startDate) educationHtml += edu.startDate;
            if (edu.startDate && edu.endDate) educationHtml += ' - ';
            if (edu.endDate) educationHtml += edu.endDate;
            educationHtml += '</div>';
        }
        
        if (edu.gpa) {
            educationHtml += '<p>GPA: ' + edu.gpa + '</p>';
        }
        
        if (edu.courses && edu.courses.length > 0) {
            educationHtml += '<div class="education-courses">';
            educationHtml += '<strong>Courses:</strong> ';
            educationHtml += edu.courses.join(', ');
            educationHtml += '</div>';
        }
        
        educationHtml += '</div>';
    });
    document.getElementById("education").innerHTML = educationHtml;
}

// Contact Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const contactStatus = document.getElementById('contactStatus');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            submitButton.disabled = true;
            
            fetch('send_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                contactStatus.style.display = 'block';
                contactStatus.className = 'contact-status ' + (data.success ? 'success' : 'error');
                contactStatus.textContent = data.message;
                
                if (data.success) {
                    contactForm.reset();
                }
            })
            .catch(error => {
                contactStatus.style.display = 'block';
                contactStatus.className = 'contact-status error';
                contactStatus.textContent = 'Network error. Please try again.';
                console.error('Contact form error:', error);
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                
                // Hide status message after 5 seconds
                setTimeout(() => {
                    contactStatus.style.display = 'none';
                }, 5000);
            });
        });
    }
});

// Navigation active link management
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation links
    const navLinks = document.querySelectorAll('.topnav a[href^="#"]');
    const sections = document.querySelectorAll('div[id]');
    
    // Function to update active navigation link
    function updateActiveLink() {
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 70; // Account for fixed nav height
            const sectionHeight = section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                currentSection = section.id;
            }
        });
        
        // Update navigation links
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    }
    
    // Add smooth scrolling to navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 60; // Account for fixed nav
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Update active link on scroll
    window.addEventListener('scroll', updateActiveLink);
    
    // Initial update
    updateActiveLink();
});

//https://www.youtube.com/watch?v=UkB-zKNBVTo&list=PLv1CRNciwsrf_DA7Yl3_kdsqYjbjbMB8r&index=3



