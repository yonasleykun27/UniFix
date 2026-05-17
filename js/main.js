// PHP API Migration Complete

const TRANSLATIONS = {
    en: {
        loginTitle: "UniFix Login", loginSubtitle: "University Problem Reporting System", 
        loginBtn: "Login", noAccount: "Don't have an account?",
        lblUsername: "Username", lblPassword: "Password",
        phUsername: "Enter Username", phPassword: "Enter Password", 
        welcome: "Welcome", logout: "Logout", dashboard: "Dashboard",
        darkMode: "Dark Mode", lightMode: "Light Mode", langName: "Amharic",
        successMsg: "Operation Successful", errorMsg: "An error occurred",
        confirmTitle: "Are you sure?", confirmBtn: "Yes, Proceed", cancelBtn: "Cancel", closeBtn: "Close",
        
        registerStudent: "Register as Student", registerTeacher: "Register as Teacher", registerAccount: "Register Account",
        regTitleStud: "Student Registration", regSubtitleStud: "Fill all fields and verify your ID card.",
        regTitleTeach: "Teacher Registration", regSubtitleTeach: "Faculty & Staff Account Creation",
        fullName: "Full Name", studentId: "Student ID", staffId: "Staff ID",
        username: "Username", password: "Password", dept: "Department", year: "Year of Study", 
        block: "Block Number", dorm: "Dorm Number",
        uploadFront: "Upload Front ID", uploadBack: "Upload Back ID (Barcode)", 
        idVerifyTitle: "ID Card Verification", idVerifyDesc: "1. Upload Back ID image. 2. Click Scan to verify.",
        scanBtn: "Scan & Verify ID",
        backToLogin: "Back to Login", 
        verifyFirst: "Verify ID First",
        
        mobileCamBtn: "📷 Open Camera & Scan",
        camTitleFront: "Step 1: Capture Front ID",
        camTitleBack: "Step 2: Capture Back ID (Barcode)",
        btnCapture: "Capture Photo",
        btnRetake: "Retake",
        btnUse: "Use Photo",
        camError: "Camera access denied. Please use file upload.",
        
        phFullName: "Enter Full Name", phStudentId: "DBU...", phStaffId: "DBU...",
        phUsernameStud: "stud12345", phUsernameTech: "tech1234",
        phDept: "e.g. Software Eng", phYear: "e.g. 3", phBlock: "Block No", phDorm: "Dorm No",

        fillMandatory: "Please fill all mandatory fields correctly",
        nameReq: "Full Name is required",
        nameNumError: "Full Name cannot contain numbers",
        idReq: "ID Number is required",
        userReq: "Username is required",
        passReq: "Password is required",
        passMinLen: "Password must be at least 6 characters", 
        deptReq: "Department is required",
        deptNumError: "Department Name cannot contain numbers",
        yearReq: "Year is required",
        blockReq: "Block Number is required",
        blockNumError: "Block Number must contain only digits",
        dormReq: "Dorm Number is required",
        dormNumError: "Dorm Number must contain only digits",
        
        enterIdFirst: "Please enter your ID first",
        uploadBackIdReq: "Please upload the Back ID image or use Camera",
        initScan: "Processing Image...",
        idMatch: "Identity Confirmed!",
        idMismatch: "ID Mismatch! Barcode does not match input.",
        noBarcode: "No readable barcode found. Try a clearer image.",
        userDuplicate: "Username already exists.",
        idDuplicate: "This ID is already registered.",
        usernameStudReq: "Username must be 'stud' followed by 5 digits",
        usernameTechReq: "Username must be 'tech' followed by 4 digits",
        successRedirect: "Account Created! Redirecting...",
        
        reportIssue: "Report Issue", myHistory: "My History", submit: "Submit Report",
        category: "Category", phone: "Phone Number", urgency: "Urgency", description: "Description",
        status: "Status", actions: "Actions", date: "Date",
        low: "Low", medium: "Medium", high: "High", urgent: "Urgent",
        editReportTitle: "Edit Report", viewDetailsTitle: "View Details", saveChanges: "Save Changes", deleteConfirmMsg: "Delete this report?",
        reasonDecline: "Reason", reportRemoved: "Report removed from view.",
        totalReports: "Total Reports", incomingPending: "Incoming Pending Reports", taskProgress: "Task Progress Tracking",
        manageReports: "Manage Reports", userDb: "User Database", 
        noPendingMsg: "No reports assigned to you currently.",
        filterStatus: "Filter by Status", myTasks: "My Handled Tasks", allTasks: "All University Tasks",
        finished: "Finished", declined: "Declined",
        reporter: "Reporter", assignedTo: "Assigned To",
        allUsers: "All", students: "Students", teachers: "Teachers", solvers: "Solvers",
        role: "Role", warnings: "Warnings", id: "ID",
        reportDetails: "Report Details", userProfile: "User Profile",
        sendWarning: "Send Warning ⚠️", removeUser: "Remove User 🗑️",
        assignBtn: "Assign", declineBtn: "Decline", viewBtn: "View", manageBtn: "Manage",
        banned: "BANNED", active: "Active",
        userDeletedCascade: "User and all their reports have been deleted permanently.",
        addStaff: "Add Staff", staffRole: "Role", jobTitle: "Job Title", createAcc: "Create Account",
        warningReason: "Reason for Warning",
        activeTasks: "Active Tasks", jobHistory: "Job History",
        startJob: "Start Job", finishJob: "Finish Job",
        locationDetails: "Location & Details", reporterInfo: "Reporter Info",
        jobFinishedMsg: "Mark this job as finished?",
        bannedMsg: "Account Banned.",
        mandatoryMsg: "Please fill in all mandatory fields (Category, Phone, and Description).",
        analytics: "Analytics", pending: "Pending", resolved: "Resolved", totalUsers: "Total Users",
        categoryDist: "Category Distribution", statusChart: "Status Chart", solverPerf: "Solver Performance",
        photoEvidence: "Photo Evidence", noPhoto: "No photo attached",
        accountInfo: "Account Information", studentDetails: "Student Details", idCardPhotos: "ID Card Photos",
        frontId: "Front of ID", backId: "Back of ID (Barcode)", notUploaded: "Not uploaded",
        disciplinaryRecord: "Disciplinary Record", registeredDate: "Registered",
        ticketChat: "Ticket Chat",
        sendMsg: "Send",
        typeMsg: "Type a message...",
        slaBreached: "⏱️ SLA BREACHED",
        slaRemaining: "SLA Remaining",
        delegate: "Delegate",
        delegateTo: "Delegate To",
        delegationNote: "Delegation Note",
        noMessages: "No messages yet",
        assignedBy: "Assigned By",
        delegatedFrom: "Delegated From",
        deadline: "Deadline",
        confirmDelete: "Delete this record permanently?",
        deletedMsg: "Record deleted successfully.",
        noUserReports: "No reports found.",
        navHome: "Home", navAbout: "About", navSignIn: "Sign In",
        heroTagline: "One Report. Real Solutions. A Better Campus.",
        heroMotto: "Ethiopia's University Problem Reporting & Tracking Platform",
        heroRotator1: "Issue Resolution",
        heroRotator2: "Smart Ticketing",
        heroRotator3: "Real-Time Tracking",
        sphereText1: "Smart",
        sphereText2: "Campus",
        statResolved: "Issues Resolved",
        statCampuses: "Campuses Supported",
        statUptime: "Uptime Guarantee",
        heroDesc: "Report, track and resolve university campus problems in one place. Built for Ethiopian universities.",
        heroCta: "Report an Issue", heroCtaSignIn: "Sign In Now",
        aboutTitle: "About UniFix",
        aboutSubtitle: "A smarter way to manage university campus issues — transparently and efficiently.",
        howItWorks: "How It Works",
        aboutCard1Title: "For Students",
        aboutCard1Desc: "Report dorm, cafeteria, academic and facility issues. Track your submissions in real time.",
        aboutCard2Title: "For Teachers",
        aboutCard2Desc: "Submit lab, facility and administrative concerns. Stay informed on every resolution step.",
        aboutCard3Title: "Fast & Transparent",
        aboutCard3Desc: "Admins review and assign every ticket to the right specialist. Every step tracked until resolved.",
        step1Title: "Submit", step1Desc: "Student or teacher reports an issue",
        step2Title: "Review", step2Desc: "Admin reviews and verifies the report",
        step3Title: "Assign", step3Desc: "Ticket assigned to a specialist solver",
        step4Title: "Resolve", step4Desc: "Issue fixed and marked complete",
        footerDesc: "Ethiopia's smart university campus issue management platform.",
        footerRights: "All rights reserved.",
        footerLinks: "Quick Links",
        footerContact: "Get Support",
        loginWelcome: "Welcome Back",
        loginWelcomeDesc: "Sign in to your UniFix account",
        navContact: "Contact us",
        whyJoinTitle: "Why Join UniFix Platform",
        featureStudent1: "Report dorm, cafeteria, and facility issues instantly",
        featureStudent2: "Simple submission process and easy-to-follow tracking",
        featureStudent3: "Real-time notifications on issue status",
        featureTeacher1: "Submit lab, academic, and administrative concerns",
        featureTeacher2: "Direct communication channel with facility managers",
        featureTeacher3: "Access your submission history anytime",
        featureSystem1: "Check issue resolution progress in real-time",
        featureSystem2: "Advanced system for assignment and monitoring",
        featureSystem3: "24/7 multi-lingual support (English & Amharic)",
        footerPlatform: "Platform",
        footerStudents: "Students",
        footerTeachers: "Teachers",
        footerSignIn: "Sign In"
    },
    am: {
        loginTitle: "UniFix መግቢያ", loginSubtitle: "የዩኒቨርሲቲ ችግር ሪፖርት ማድረጊያ",
        loginBtn: "ግባ", noAccount: "መለያ የለዎትም?",
        lblUsername: "የተጠቃሚ ስም", lblPassword: "የይለፍ ቃል", 
        phUsername: "የተጠቃሚ ስም ያስገቡ", phPassword: "የይለፍ ቃል ያስገቡ",
        welcome: "እንኳን ደህና መጡ", logout: "ውጣ", dashboard: "ዳሽቦርድ",
        darkMode: "ጨለማ", lightMode: "ብርሃን", langName: "English",
        successMsg: "ተሳክቷል", errorMsg: "ስህተት ተፈጥሯል",
        confirmTitle: "እርግጠኛ ነዎት?", confirmBtn: "አዎ", cancelBtn: "ይቅር", closeBtn: "ዝጋ",
        
        registerStudent: "እንደ ተማሪ ይመዝገቡ", registerTeacher: "እንደ መምህር ይመዝገቡ", registerAccount: "መለያ ይፍጠሩ",
        regTitleStud: "የተማሪ ምዝገባ", regSubtitleStud: "እባክዎ ሁሉንም መረጃዎች ይሙሉ እና መታወቂያዎን ያረጋግጡ።",
        regTitleTeach: "የመምህራን ምዝገባ", regSubtitleTeach: "የመምህራን እና ሰራተኞች መለያ መፍጠሪያ",
        fullName: "ሙሉ ስም", studentId: "የተማሪ መታወቂያ", staffId: "የመለያ ቁጥር",
        username: "የተጠቃሚ ስም", password: "የይለፍ ቃል", dept: "የትምህርት ክፍል", year: "የትምህርት ዘመን", 
        block: "ብሎክ ቁጥር", dorm: "ዶርም ቁጥር",
        uploadFront: "የፊት መታወቂያ ጫን", uploadBack: "የኋላ መታወቂያ ጫን",
        idVerifyTitle: "መታወቂያ ማረጋገጫ", idVerifyDesc: "1. የኋላ መታወቂያ ፎቶ ይጫኑ። 2. 'ስካን' የሚለውን ይጫኑ።",
        scanBtn: "የኋላ መታወቂያ ስካን",
        backToLogin: "ወደ መግቢያ ተመለስ", scanning: "በመፈለግ ላይ...", idMatch: "ተረጋግጧል!", 
        idMismatch: "መታወቂያው አይዛመድም!", noBarcode: "ባርኮድ አልተገኘም",
        verifyFirst: "መጀመሪያ መታወቂያዎን ያረጋግጡ",

        mobileCamBtn: "📷 ካሜራ ይክፈቱ",
        camTitleFront: "ደረጃ 1: የፊት መታወቂያ ፎቶ ያንሱ",
        camTitleBack: "ደረጃ 2: የኋላ መታወቂያ (ባርኮድ) ፎቶ ያንሱ",
        btnCapture: "ፎቶ አንሳ",
        btnRetake: "ድጋሚ አንሳ",
        btnUse: "ይህንን ተጠቀም",
        camError: "ካሜራው አልሰራም። እባክዎ ፍቃድ ይስጡ።",

        phFullName: "ሙሉ ስም ያስገቡ", phStudentId: "DBU...", phStaffId: "DBU...",
        phUsernameStud: "stud12345", phUsernameTech: "tech1234",
        phDept: "ምሳሌ፡ Software Eng", phYear: "ምሳሌ፡ 3", phBlock: "ብሎክ ቁጥር", phDorm: "ዶርም ቁጥር",

        fillMandatory: "እባክዎ ሁሉንም አስፈላጊ መረጃዎች በትክክል ይሙሉ",
        nameReq: "ሙሉ ስም ማስገባት ግዴታ ነው",
        nameNumError: "ሙሉ ስም ቁጥር መያዝ የለበትም",
        idReq: "መታወቂያ ቁጥር ማስገባት ግዴታ ነው",
        userReq: "የተጠቃሚ ስም ማስገባት ግዴታ ነው",
        passReq: "የይለፍ ቃል ማስገባት ግዴታ ነው",
        passMinLen: "የይለፍ ቃል ቢያንስ 6 ሆሄያት መሆን አለበት", 
        deptReq: "የትምህርት ክፍል ማስገባት ግዴታ ነው",
        deptNumError: "የትምህርት ክፍል ስም ቁጥር መያዝ የለበትም",
        yearReq: "የትምህርት ዘመን ማስገባት ግዴታ ነው",
        blockReq: "የብሎክ ቁጥር ማስገባት ግዴታ ነው",
        blockNumError: "የብሎክ ቁጥር ቁጥሮችን ብቻ መያዝ አለበት",
        dormReq: "የዶርም ቁጥር ማስገባት ግዴታ ነው",
        dormNumError: "የዶርም ቁጥር ቁጥሮችን ብቻ መያዝ አለበት",

        enterIdFirst: "እባክዎ መጀመሪያ መታወቂያ ቁጥር ያስገቡ",
        uploadBackIdReq: "እባክዎ የኋላ መታወቂያ ምስል ይጫኑ",
        initScan: "ባርኮድ በመፈለግ ላይ...",
        userDuplicate: "ይህ የተጠቃሚ ስም ተይዟል",
        idDuplicate: "ይህ መለያ ቁጥር በሌላ ተጠቃሚ ተመዝግቧል",
        usernameStudReq: "የተጠቃሚ ስም 'stud' እና 5 ቁጥሮች መሆን አለበት",
        usernameTechReq: "የተጠቃሚ ስም 'tech' እና 4 ቁጥሮች መሆን አለበት",
        successRedirect: "ተሳክቷል! ወደ መግቢያ በመውሰድ ላይ...",

        reportIssue: "ችግር ሪፖርት አድርግ", myHistory: "የኔ ታሪክ", submit: "ላክ",
        category: "ምድብ", phone: "ስልክ ቁጥር", urgency: "አስቸኳይነት", description: "ዝርዝር",
        status: "ሁኔታ", actions: "ተግባራት", date: "ቀን",
        low: "ዝቅተኛ", medium: "መካከለኛ", high: "ከፍተኛ", urgent: "አስቸኳይ",
        editReportTitle: "ሪፖርት አስተካክል", viewDetailsTitle: "ዝርዝር ይመልከቱ",
        saveChanges: "ለውጦችን አስቀምጥ", deleteConfirmMsg: "ይህንን ሪፖርት ለመሰረዝ ይፈልጋሉ?",
        reasonDecline: "ምክንያት", reportRemoved: "ሪፖርቱ ከእይታ ተወግዷል።",
        totalReports: "ጠቅላላ ሪፖርቶች", incomingPending: "በመጠባበቅ ላይ ያሉ ሪፖርቶች",
        taskProgress: "የስራ ሂደት ክትትል",
        manageReports: "ሪፖርቶችን ያስተዳድሩ", userDb: "የተጠቃሚዎች መረጃ",
        noPendingMsg: "በአሁኑ ጊዜ ለእርስዎ የተመደበ ሪፖርት የለም።",
        filterStatus: "በሁኔታ አጣራ", myTasks: "በእኔ የተሰሩ ስራዎች", allTasks: "የዩኒቨርሲቲው አጠቃላይ ስራዎች",
        finished: "ተጠናቀዋል", declined: "ውድቅ ተደርገዋል",
        reporter: "ሪፖርት አቅራቢ", assignedTo: "የተመደበለት",
        allUsers: "ሁሉም", students: "ተማሪዎች", teachers: "መምህራን", solvers: "ባለሙያዎች",
        role: "ሚና", warnings: "ማስጠንቀቂያ", id: "መለያ",
        reportDetails: "የሪፖርት ዝርዝር", userProfile: "የተጠቃሚ መረጃ",
        sendWarning: "ማስጠንቀቂያ ላክ ⚠️", removeUser: "ተጠቃሚውን አስወግድ 🗑️",
        assignBtn: "መድብ", declineBtn: "ሰርዝ", viewBtn: "ይመልከቱ", manageBtn: "አስተዳድር",
        banned: "ታግዷል", active: "ንቁ",
        userDeletedCascade: "ተጠቃሚው እና ያቀረባቸው ሪፖርቶች በሙሉ ተሰርዘዋል።",
        addStaff: "ሰራተኛ ጨምር", staffRole: "ሚና", jobTitle: "የሥራ መደብ", createAcc: "መለያ ፍጠር",
        warningReason: "የማስጠንቀቂያ ምክንያት",
        activeTasks: "ንቁ ስራዎች", jobHistory: "የስራ ታሪክ",
        startJob: "ስራ ጀምር", finishJob: "ስራ ጨርስ",
        locationDetails: "የቦታ እና ዝርዝር መረጃ", reporterInfo: "የሪፖርት አቅራቢ መረጃ",
        jobFinishedMsg: "ይህንን ስራ እንደተጠናቀቀ ምልክት ማድረግ ይፈልጋሉ?",
        bannedMsg: "መለያዎ ታግዧል",
        mandatoryMsg: "እባክዎ ሁሉንም አስፈላጊ መስኮች ይሙሉ (ምድብ፣ ስልክ እና መግለጫ)።",
        analytics: "ስታቲስቲክስ", pending: "በመጠባበቅ ላይ", resolved: "የተፈቱ", totalUsers: "ጠቅላላ ተጠቃሚዎች",
        categoryDist: "በምድብ ስርጭት", statusChart: "የሁኔታ ገበታ", solverPerf: "የባለሙያዎች አፈጻጸም",
        photoEvidence: "የፎቶ ማስረጃ", noPhoto: "ምንም ፎቶ አልተያያዘም",
        accountInfo: "የመለያ መረጃ", studentDetails: "የተማሪ ዝርዝር", idCardPhotos: "የመታወቂያ ፎቶዎች",
        frontId: "የፊት መታወቂያ", backId: "የኋላ መታወቂያ", notUploaded: "አልተጫነም",
        disciplinaryRecord: "የዲሲፕሊን ሪከርድ", registeredDate: "የተመዘገበበት ቀን",
        phPassword: "የይለፍ ቃል ያስገቡ",
        ticketChat: "የቲኬት ውይይት",
        sendMsg: "ላክ",
        typeMsg: "መልዕክት ይጻፉ...",
        slaBreached: "⏱️ ጊዜ አልፏል",
        slaRemaining: "ቀሪ ጊዜ",
        delegate: "አስተላልፍ",
        delegateTo: "ለማን ይተላለፍ?",
        delegationNote: "የማስተላለፊያ ምክንያት",
        noMessages: "ምንም መልዕክት የለም",
        assignedBy: "የመደበው",
        delegatedFrom: "የተላለፈው ከ",
        deadline: "መጨረሻ ቀን",
        confirmDelete: "ይህ መረጃ ሙሉ በሙሉ ለመሰረዝ ይፈልጋሉ?",
        deletedMsg: "መረጃው ተሰርዟል።",
        noUserReports: "ምንም ሪፖርቶች አልተገኙም።",
        navHome: "ዋና ገጽ", navAbout: "ስለ እኛ", navSignIn: "ግባ",
        heroTagline: "አንድ ሪፖርት። እውነተኛ መፍትሄ። የተሻለ ካምፓስ።",
        heroMotto: "የኢትዮጵያ ዩኒቨርሲቲ ችግር ሪፖርት እና ክትትል ስርዓት",
        heroRotator1: "የችግር አፈታት",
        heroRotator2: "ዘመናዊ ትኬት",
        heroRotator3: "ቀጥታ ክትትል",
        sphereText1: "ዘመናዊ",
        sphereText2: "ግቢ",
        statResolved: "ችግሮች ተፈተዋል",
        statCampuses: "ግቢዎች ይደገፋሉ",
        statUptime: "ሰዓታት አገልግሎት",
        heroDesc: "የዩኒቨርሲቲ ችግሮችን ሪፖርት ያድርጉ፣ ክትትል ያድርጉ፣ እና ፈጣን መፍትሄ ያግኙ። ለኢትዮጵያ ዩኒቨርሲቲዎች የተሰራ።",
        heroCta: "ችግር ሪፖርት አድርግ", heroCtaSignIn: "አሁን ግባ",
        aboutTitle: "ስለ UniFix",
        aboutSubtitle: "ዘመናዊ የዩኒቨርሲቲ ችግር አስተዳደር ስርዓት — ፈጣን፣ ግልጽ እና ቀልጣፋ።",
        howItWorks: "እንዴት ይሠራል",
        aboutCard1Title: "ለተማሪዎች",
        aboutCard1Desc: "የዶርም፣ ካፌ፣ ትምህርታዊ እና ፋሲሊቲ ችግሮችን ሪፖርት ያድርጉ። ሂደቱን ደረጃ በደረጃ ይከታተሉ።",
        aboutCard2Title: "ለመምህራን",
        aboutCard2Desc: "የላብ፣ ፋሲሊቲ እና አስተዳደራዊ ችግሮችን ያቅርቡ። የሂደቱን ክንውን ሁሉ ይከታተሉ።",
        aboutCard3Title: "ፈጣን እና ግልጽ",
        aboutCard3Desc: "አስተዳዳሪዎቹ ሁሉንም ቲኬቶች ይገመግማሉ። ሪፖርቱ እስኪፈታ ድረስ ሁሉ ነገር ይከታተላል።",
        step1Title: "አቅርብ", step1Desc: "ተማሪ ወይም መምህር ችግሩን ሪፖርት ያደርጋሉ",
        step2Title: "ግምግም", step2Desc: "አስተዳዳሪ ሪፖርቱን ይመረምራሉ",
        step3Title: "ሰጥ", step3Desc: "ቲኬቱ ለባለሙያ ሰሪ ይሰጣል",
        step4Title: "ፍታ", step4Desc: "ችግሩ ተፈቶ ተጠናቅቋል ተብሎ ይሰምናል",
        footerDesc: "የኢትዮጵያ ዩኒቨርሲቲ ዘመናዊ ችግር አስተዳደር ስርዓት።",
        footerRights: "መብቶቹ ሁሉ የተጠበቁ ናቸው።",
        footerLinks: "ፈጣን አገናኞች",
        footerContact: "ድጋፍ አግኙ",
        loginWelcome: "እንኳን ደህና መጡ",
        loginWelcomeDesc: "ወደ UniFix መለያዎ ይግቡ",
        navContact: "ያግኙን",
        whyJoinTitle: "ለምን UniFix መድረክን ይቀላቀላሉ",
        featureStudent1: "የዶርም፣ ካፌ እና ፋሲሊቲ ችግሮችን ወዲያውኑ ሪፖርት ያድርጉ",
        featureStudent2: "ቀላል የማስረከቢያ ሂደት እና ለመከታተል ቀላል",
        featureStudent3: "የችግር ሁኔታ ላይ የእውነተኛ ጊዜ ማሳወቂያዎች",
        featureTeacher1: "የላብራቶሪ፣ የአካዳሚክ እና የአስተዳደር ጉዳዮችን ያቅርቡ",
        featureTeacher2: "ከፋሲሊቲ አስተዳዳሪዎች ጋር ቀጥተኛ የመገናኛ መስመር",
        featureTeacher3: "የማስረከቢያ ታሪክዎን በማንኛውም ጊዜ ይድረሱ",
        featureSystem1: "የችግር መፍቻ ሂደትን በእውነተኛ ጊዜ ያረጋግጡ",
        featureSystem2: "የላቀ የምደባ እና የክትትል ስርዓት",
        featureSystem3: "24/7 የብዙ ቋንቋ ድጋፍ (እንግሊዝኛ እና አማርኛ)",
        footerPlatform: "መድረክ",
        footerStudents: "ተማሪዎች",
        footerTeachers: "መምህራን",
        footerSignIn: "ግባ"
    }
};

const System = {
    currentUser: null,
    cachedReports: [], 
    cachedUsers: [],
    isProcessing: false, // Global guard for critical actions

    setLoading: function(btn, isLoading, loadingText = "Processing...") {
        if (!btn) return;
        if (isLoading) {
            btn.dataset.originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${loadingText}`;
        } else {
            btn.disabled = false;
            if (btn.dataset.originalHtml) {
                btn.innerHTML = btn.dataset.originalHtml;
                delete btn.dataset.originalHtml;
            }
        }
    },

    init: async function() {
        const storedUser = JSON.parse(localStorage.getItem('unifix_user'));
        if (storedUser) this.currentUser = storedUser;

        await this.refreshData();

        if (storedUser) {
            const validUser = this.cachedUsers.find(u => u.username === storedUser.username);
            if (!validUser || validUser.isBanned) {
                this.logout();
            } else {
                this.currentUser = validUser;
                localStorage.setItem('unifix_user', JSON.stringify(validUser));
            }
        }
        
        this.applyTheme();
        this.applyLanguage();
        this.initDOMManipulation();
    },

    refreshData: async function() {
        try {
            const res = await fetch('get_data.php?t=' + Date.now(), { credentials: 'include' });
            const data = await res.json();
            if (data.success) {
                this.cachedUsers = data.users;
                this.cachedReports = data.reports;
                return true;
            }
            return false;
        } catch (e) {
            console.error("Sync Error:", e);
            return false;
        }
    },

    getData: function() {
        return { users: this.cachedUsers, reports: this.cachedReports, currentUser: this.currentUser };
    },

    login: async function(username, password) {
        try {
            const response = await fetch('login.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });

            const result = await response.json();

            if (result.success) {
                this.currentUser = result.user;
                localStorage.setItem('unifix_user', JSON.stringify(result.user));
                return { success: true, role: result.role };
            } else {
                if (result.message === "BANNED") {
                    const lang = localStorage.getItem('unifix_lang') || 'en';
                    return { success: false, message: TRANSLATIONS[lang].bannedMsg };
                }
                return { success: false, message: result.message };
            }
        } catch (error) {
            console.error('Login error:', error);
            return { success: false, message: "Login failed due to a server error." };
        }
    },

    detectRole: function(username) {
        if (username.startsWith('admin')) return 'Admin';
        if (username.startsWith('stud') && username.length >= 9) return 'Student'; 
        if (username.startsWith('tech') && username.length >= 8) return 'Teacher'; 
        if (username.startsWith('solver')) return 'Solver';
        return null;
    },

    logout: async function() {
        try { await fetch('logout.php', { credentials: 'include' }); } catch(e) {}
        this.currentUser = null;
        localStorage.removeItem('unifix_user');
        window.location.href = 'index.html';
    },

    checkAuth: function(requiredRole) {
        const user = JSON.parse(localStorage.getItem('unifix_user'));
        if (!user) { window.location.href = 'index.html'; return null; }
        if (requiredRole && requiredRole !== 'Any' && user.role !== requiredRole) {
            window.location.href = 'index.html'; return null;
        }
        
        this.currentUser = user;
        const els = document.querySelectorAll('.user-display-name');
        els.forEach(el => el.innerText = user.fullName);
        
        const warnBanner = document.getElementById('warningBanner');
        if(warnBanner) {
            if(user.warnings > 0) {
                warnBanner.classList.remove('d-none');
                document.getElementById('warnCount').innerText = user.warnings;
                const reasonDiv = document.getElementById('warnReason');
                if(reasonDiv) {
                    if (user.warningHistory && user.warningHistory.length > 0) {
                        let html = '';
                        user.warningHistory.forEach((w, i) => {
                            html += `<div class="mb-1"><strong>Warning ${i+1} (${w.date}):</strong> ${w.reason}</div>`;
                        });
                        reasonDiv.innerHTML = html;
                        reasonDiv.classList.remove('d-none');
                    } else if (user.lastWarningReason) {
                        reasonDiv.innerText = user.lastWarningReason;
                        reasonDiv.classList.remove('d-none');
                    }
                }
            } else {
                warnBanner.classList.add('d-none');
            }
        }
        
        this.refreshData().then(() => {
            if(typeof window.loadReports === 'function') window.loadReports(true);
            if(typeof window.loadTasks === 'function') window.loadTasks(true);
            if(typeof window.loadUsers === 'function') window.loadUsers();
            if(typeof window.loadHistory === 'function') window.loadHistory();
        });

        return user;
    },

    register: async function(newUser) {
        await this.refreshData();
        const lang = localStorage.getItem('unifix_lang') || 'en';

        if (this.cachedUsers.find(u => u.username === newUser.username)) {
            return { success: false, message: TRANSLATIONS[lang].userDuplicate };
        }
        if (this.cachedUsers.find(u => u.id === newUser.id)) {
            return { success: false, message: TRANSLATIONS[lang].idDuplicate };
        }
        
        try {
            const res = await fetch('register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newUser)
            });
            const result = await res.json();
            if (result.success) {
                await this.refreshData();
                return { success: true, message: TRANSLATIONS[lang].successMsg };
            }
            return { success: false, message: result.message };
        } catch(e) {
            return { success: false, message: e.message };
        }
    },

    addStaff: async function(staffData) {
        await this.refreshData();
        const sameRoleUsers = this.cachedUsers.filter(u => u.role === staffData.role);
        let maxNum = 1000;
        sameRoleUsers.forEach(u => {
            if (u.id) {
                const parts = u.id.split('-');
                const num = parseInt(parts[parts.length - 1]);
                if (!isNaN(num) && num > maxNum) {
                    maxNum = num;
                }
            }
        });
        const nextNum = maxNum + 1;
        
        let prefix = staffData.role === 'Admin' ? "DBU-ADM-" : "DBU-SLV-";
        staffData.id = `${prefix}${nextNum}`;

        if (this.cachedUsers.find(u => u.username === staffData.username)) {
            return { success: false, message: "Username exists." };
        }

        try {
            const res = await fetch('register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(staffData)
            });
            const result = await res.json();
            if (result.success) {
                await this.refreshData();
                return { success: true };
            }
            return { success: false, message: result.message };
        } catch (e) {
            return { success: false, message: e.message };
        }
    },

    submitReport: async function(newReport, photoFile = null) {
        try {
            await this.refreshData();
            
            const admins = this.cachedUsers.filter(u => u.role === 'Admin');
            admins.sort((a, b) => a.username.localeCompare(b.username));

            if (admins.length > 0) {
                const totalReportsEver = this.cachedReports.length;
                const adminIndex = totalReportsEver % admins.length;
                newReport.assignedPendingAdmin = admins[adminIndex].username;
            } else {
                newReport.assignedPendingAdmin = null;
            }

            let res;
            if (photoFile) {
                const formData = new FormData();
                formData.append('action', 'submit');
                formData.append('report', JSON.stringify(newReport));
                formData.append('photo', photoFile);
                res = await fetch('manage_reports.php', { method: 'POST', credentials: 'include', body: formData });
            } else {
                res = await fetch('manage_reports.php', {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'submit', report: newReport })
                });
            }
            const result = await res.json();
            if (result.success) {
                await this.refreshData();
                return { success: true };
            }
            return { success: false };
        } catch(e) {
            console.error(e);
            return { success: false };
        }
    },

    updateReportStatus: async function(id, status, assignedToJobTitle = null, declineReason = null, actingAdmin = null) {
        await this.refreshData();
        
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(!report) return false;

        const updateData = { action: 'updateStatus', id: report.firebaseId, status: status };

        if (status === 'Assigned' && assignedToJobTitle) {
            updateData.assignedTo = assignedToJobTitle;
            updateData.actingAdmin = actingAdmin;

            const eligibleSolvers = this.cachedUsers.filter(u => 
                u.role === 'Solver' && u.jobTitle === assignedToJobTitle && !u.isBanned && !u.isOnLeave
            );
            
            if (eligibleSolvers.length > 0) {
                eligibleSolvers.sort((a, b) => a.username.localeCompare(b.username));
                const totalInRole = this.cachedReports.filter(r => r.assignedTo === assignedToJobTitle).length;
                const solverIndex = totalInRole % eligibleSolvers.length;
                const selectedSolver = eligibleSolvers[solverIndex];

                updateData.solverUsername = selectedSolver.username;
                updateData.solverName = selectedSolver.fullName;
            } else {
                this.showToast("No specialist found for: " + assignedToJobTitle + ". searching General Tech...", "info");
                const fallbackPool = this.cachedUsers.filter(u => u.jobTitle === "Staff General Technician" && !u.isBanned);
                if(fallbackPool.length > 0) {
                    const selected = fallbackPool[0];
                    updateData.solverUsername = selected.username;
                    updateData.solverName = selected.fullName;
                    updateData.assignedTo = "Staff General Technician";
                }
            }
        }

        if(declineReason) updateData.declineReason = declineReason;

        try {
            const res = await fetch('manage_reports.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updateData)
            });
            await this.refreshData(); 
            return true;
        } catch (e) {
            console.error("Update Failed:", e);
            return false;
        }
    },

    getTrans: function(key) {
        const lang = localStorage.getItem('unifix_lang') || 'en';
        return TRANSLATIONS[lang][key] || key;
    },

    applyLanguage: function() {
        const lang = localStorage.getItem('unifix_lang') || 'en';
        const texts = TRANSLATIONS[lang];
        
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (texts[key]) {
                if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    el.placeholder = texts[key];
                } else {
                    el.innerText = texts[key];
                }
            }
        });

        const userIn = document.getElementById('username');
        const passIn = document.getElementById('password');
        if (userIn && texts.phUsername) userIn.placeholder = texts.phUsername;
        if (passIn && texts.phPassword) passIn.placeholder = texts.phPassword;

        const langBtnText = document.getElementById('langBtnText');
        if (langBtnText) langBtnText.innerText = texts.langName;
    },

    updateReportContent: async function(id, newData) {
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(!report) return false;
        await fetch('manage_reports.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'updateContent', id: report.firebaseId, newData: newData })
        });
        await this.refreshData();
        return true;
    },

    warnUser: async function(username, reason) {
        const res = await fetch('manage_users.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'warn', username: username, reason: reason })
        });
        const result = await res.json();
        if (result.success) {
            await this.refreshData();
            return `Warning sent. Total: ${result.warnings}/3`;
        }
        return "User not found or error.";
    },

    updateUserContact: async function(username, email, phone) {
        try {
            const res = await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'updateContact', username: username, email: email, phone: phone })
            });
            const result = await res.json();
            if (result.success) await this.refreshData();
            return result;
        } catch (e) {
            console.error(e);
            return { success: false, message: e.message };
        }
    },

    deleteUser: async function(username) {
        try {
            await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', username: username })
            });
            await this.refreshData();
            return true;
        } catch (e) {
            console.error(e);
            return false;
        }
    },

    toggleLeave: async function(username) {
        try {
            const res = await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggleLeave', username: username })
            });
            await this.refreshData();
            return await res.json();
        } catch (e) {
            console.error(e);
            return { success: false };
        }
    },

    softDeleteReport: async function(id) {
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(report) {
            await fetch('manage_reports.php', { method: 'POST', credentials: 'include', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'softDelete', id: report.firebaseId }) });
            await this.refreshData();
            return true;
        }
        return false;
    },

    hideReportFromAdmin: async function(id) {
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(report) {
            await fetch('manage_reports.php', { method: 'POST', credentials: 'include', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'hideAdmin', id: report.firebaseId }) });
            await this.refreshData();
            return true;
        }
        return false;
    },

    hideReportFromSolver: async function(id) {
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(report) {
            await fetch('manage_reports.php', { method: 'POST', credentials: 'include', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'hideSolver', id: report.firebaseId }) });
            await this.refreshData();
            return true;
        }
        return false;
    },

    hardDeleteReport: async function(id) {
        const report = this.cachedReports.find(r => r.id === id || r.firebaseId === id);
        if(report) {
            await fetch('manage_reports.php', { method: 'POST', credentials: 'include', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ action: 'hardDelete', id: report.firebaseId }) });
            await this.refreshData();
            return true;
        }
        return false;
    },

    showToast: function(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if(!container) return;
        const bgClass = type === 'success' ? 'text-bg-success' : type === 'danger' ? 'text-bg-danger' : type === 'warning' ? 'text-bg-warning' : 'text-bg-primary';
        const toastHTML = `<div class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = toastHTML;
        container.appendChild(tempDiv.firstElementChild);
        const toast = new bootstrap.Toast(container.lastElementChild);
        toast.show();
    },

    confirmAction: function(message, callback) {
        const modalEl = document.getElementById('globalConfirmModal');
        if(modalEl) {
            document.getElementById('globalConfirmBody').innerText = message;
            const confirmBtn = document.getElementById('globalConfirmBtn');
            
            const newBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            
            newBtn.addEventListener('click', () => {
                modalInstance.hide();
                callback();
            });
            
            modalInstance.show();

            // Force z-index above any open modal (e.g. reportModal at 1070)
            modalEl.style.zIndex = '1090';
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if(backdrops.length > 0) {
                    backdrops[backdrops.length - 1].style.zIndex = '1082';
                }
            }, 10);
        } else {
            if(confirm(message)) callback();
        }
    },

    initDOMManipulation: function() {
        setInterval(() => {
            const clockEl = document.getElementById('liveClock');
            if(clockEl) {
                clockEl.innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }, 1000);
    },

    toggleTheme: function() {
        let current = localStorage.getItem('unifix_theme') || 'light';
        localStorage.setItem('unifix_theme', current === 'light' ? 'dark' : 'light');
        this.applyTheme();
    },

    applyTheme: function() {
        const theme = localStorage.getItem('unifix_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
        const icon = document.getElementById('themeIcon');
        if(icon) icon.className = theme === 'light' ? 'bi bi-moon-stars-fill' : 'bi bi-sun-fill';
    },

    toggleLanguage: function() {
        let current = localStorage.getItem('unifix_lang') || 'en';
        localStorage.setItem('unifix_lang', current === 'en' ? 'am' : 'en');
        location.reload();
    },

    fetchMessages: async function(reportId, isAdmin = false) {
        try {
            const payload = { action: 'fetch', reportId, t: Date.now() };
            if (this.currentUser) {
                payload.userRole = this.currentUser.role;           // Admin | Solver | Student | Teacher
                payload.senderUsername = this.currentUser.username; // For reporter's own admin_only messages
            }
            const res = await fetch('ticket_chat.php', { method: 'POST', credentials: 'include', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload) });
            const result = await res.json();
            return result.success ? result.messages : [];
        } catch(e) { return []; }
    },

    sendMessage: async function(reportId, message, visibility = 'public') {
        if(!this.currentUser || !message.trim()) return {success:false};
        try {
            const res = await fetch('ticket_chat.php', { method:'POST', credentials: 'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'send', reportId, senderUsername: this.currentUser.username, senderRole: this.currentUser.role, message: message.trim(), visibility}) });
            return await res.json();
        } catch(e) { return {success:false, message:e.message}; }
    },

    editMessage: async function(messageId, newMessage) {
        if(!this.currentUser || !newMessage.trim()) return {success:false};
        try {
            const res = await fetch('ticket_chat.php', { method:'POST', credentials: 'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'edit', messageId, senderUsername: this.currentUser.username, message: newMessage.trim()}) });
            return await res.json();
        } catch(e) { return {success:false, message:e.message}; }
    },

    deleteMessage: async function(messageId) {
        if(!this.currentUser) return {success:false};
        try {
            const res = await fetch('ticket_chat.php', { method:'POST', credentials: 'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'delete', messageId, senderUsername: this.currentUser.username}) });
            return await res.json();
        } catch(e) { return {success:false, message:e.message}; }
    },

    delegateTicket: async function(reportId, newSolverUsername, newSolverName, fromSolverUsername, note) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'delegate', id:reportId, newSolverUsername, newSolverName, fromSolverUsername, note}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false, message:e.message}; }
    },

    cancelDelegation: async function(reportId) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'cancelDelegation', id:reportId}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false, message:e.message}; }
    },

    acceptDelegation: async function(reportId) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'acceptDelegation', id:reportId, solverName: this.currentUser.fullName, solverUsername: this.currentUser.username}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false, message:e.message}; }
    },

    declineDelegation: async function(reportId) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'declineDelegation', id:reportId, solverName: this.currentUser.fullName, solverUsername: this.currentUser.username}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false, message:e.message}; }
    },

    setSLA: async function(reportId, hours) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'setSLA', id:reportId, hours}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false}; }
    },

    checkSLA: async function() {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'checkSLA'}) });
            return await res.json();
        } catch(e) { return {success:false}; }
    },

    clearEscalation: async function(reportId) {
        try {
            const res = await fetch('manage_reports.php', { method:'POST', credentials:'include', headers:{'Content-Type':'application/json'}, body: JSON.stringify({action:'clearEscalation', id:reportId}) });
            const result = await res.json();
            if(result.success) await this.refreshData();
            return result;
        } catch(e) { return {success:false}; }
    },

    renderChatMessages: function(msgs, containerId, isAdmin = false) {
        const chatDiv = document.getElementById(containerId);
        if(!chatDiv) return;
        chatDiv.style.background = 'var(--bs-tertiary-bg)';
        if(!msgs || msgs.length === 0) {
            chatDiv.innerHTML = '<div class="text-center text-muted small py-3"><i class="bi bi-chat-square-text"></i> No messages yet</div>';
            return;
        }
        chatDiv.innerHTML = msgs.map(m => {
            const isMine = this.currentUser && m.senderUsername === this.currentUser.username;
            const isAdminOnly   = m.visibility === 'admin_only';
            const isStudentOnly = m.visibility === 'student_only';
            const safeMsg = encodeURIComponent(m.message);

            const actionBtns = isMine ? `
                <div class="msg-actions mt-1 border-top pt-1 d-flex gap-2" style="font-size:0.7rem;">
                    <button class="btn btn-link btn-sm p-0 text-primary" style="font-size:0.7rem;" onclick="System.startEditMessage(${m.id}, '${safeMsg}')"><i class="bi bi-pencil-fill"></i> Edit</button>
                    <button class="btn btn-link btn-sm p-0 text-danger" style="font-size:0.7rem;" onclick="System.confirmDeleteMessage(${m.id})"><i class="bi bi-trash-fill"></i> Delete</button>
                </div>` : '';

            const bgStyle = isMine
                ? 'background:var(--bs-primary);color:#fff;'
                : 'background:var(--bs-secondary-bg);color:var(--bs-body-color);border:1px solid var(--bs-border-color);';

            // Private message border highlight
            let privateBorder = '';
            let privateBadge  = '';
            if (isAdminOnly) {
                privateBorder = 'border:2px solid #dc3545!important;';
                privateBadge  = `<span class="badge bg-danger ms-1" style="font-size:0.55rem;"><i class="bi bi-shield-lock-fill"></i> Admin Only</span>`;
            } else if (isStudentOnly) {
                privateBorder = 'border:2px solid #ffc107!important;';
                privateBadge  = `<span class="badge bg-warning text-dark ms-1" style="font-size:0.55rem;"><i class="bi bi-person-lock"></i> Reporter Only</span>`;
            }

            return `
            <div class="mb-2 ${isMine ? 'text-end' : ''}" id="msg-bubble-${m.id}">
                <div class="d-inline-block p-2 rounded shadow-sm text-start" style="max-width:80%;${bgStyle}${privateBorder}">
                    <div class="small fw-bold">${m.senderName || m.senderUsername} <span class="badge bg-secondary" style="font-size:0.6rem">${m.senderRole}</span>${privateBadge}</div>
                    <div id="msg-text-${m.id}">${m.message}</div>
                    <div style="font-size:0.65rem;opacity:0.7;">${m.createdAt}</div>
                    ${actionBtns}
                </div>
            </div>`;
        }).join('');
        chatDiv.scrollTop = chatDiv.scrollHeight;
    },

    startEditMessage: function(msgId, encodedText) {
        const originalText = decodeURIComponent(encodedText);
        const textEl = document.getElementById('msg-text-' + msgId);
        if(!textEl || textEl.querySelector('textarea')) return;

        textEl.innerHTML = `
            <textarea id="msg-edit-input-${msgId}" class="form-control form-control-sm mb-1" rows="2" style="min-width:180px;background:rgba(255,255,255,0.15);color:inherit;">${originalText}</textarea>
            <div class="d-flex gap-1 mt-1">
                <button class="btn btn-success btn-sm py-0" style="font-size:0.75rem;" onclick="System.saveEditMessage(${msgId})"><i class="bi bi-check-lg"></i> Save</button>
                <button class="btn btn-secondary btn-sm py-0" style="font-size:0.75rem;" onclick="System.cancelEditMessage()"><i class="bi bi-x-lg"></i> Cancel</button>
            </div>`;

        const bubble = document.getElementById('msg-bubble-' + msgId);
        if(bubble) { const a = bubble.querySelector('.msg-actions'); if(a) a.style.display = 'none'; }

        const input = document.getElementById('msg-edit-input-' + msgId);
        if(input) { input.focus(); input.setSelectionRange(input.value.length, input.value.length); }
    },

    saveEditMessage: async function(msgId) {
        const input = document.getElementById('msg-edit-input-' + msgId);
        if(!input) return;
        const newText = input.value.trim();
        if(!newText) { this.showToast('Message cannot be empty', 'warning'); return; }

        const res = await this.editMessage(msgId, newText);
        if(res.success) {
            this.showToast('Message updated.', 'success');
            if(window._chatRefreshFn) window._chatRefreshFn();
        } else {
            this.showToast(res.message || 'Failed to update', 'danger');
        }
    },

    cancelEditMessage: function() {
        if(window._chatRefreshFn) window._chatRefreshFn();
    },

    confirmDeleteMessage: function(msgId) {
        this.confirmAction('Delete this message permanently?', async () => {
            const res = await this.deleteMessage(msgId);
            if(res.success) {
                this.showToast('Message deleted.', 'info');
                if(window._chatRefreshFn) window._chatRefreshFn();
            } else {
                this.showToast(res.message || 'Failed to delete', 'danger');
            }
        });
    },

    unbanUser: async function(username) {
        try {
            const res = await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'unban', username })
            });
            return await res.json();
        } catch(e) { return { success: false, message: e.message }; }
    },

    retractWarning: async function(username) {
        try {
            const res = await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'retractWarning', username })
            });
            return await res.json();
        } catch(e) { return { success: false, message: e.message }; }
    },

    changePassword: async function(currentPassword, newPassword) {
        try {
            const res = await fetch('change_password.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ currentPassword, newPassword })
            });
            return await res.json();
        } catch(e) { return { success: false, message: e.message }; }
    },

    fetchNotifications: async function() {
        if (!this.currentUser) return { notifications: [], unreadCount: 0 };
        try {
            const res = await fetch('notifications.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'fetch', username: this.currentUser.username })
            });
            const data = await res.json();
            return data.success ? data : { notifications: [], unreadCount: 0 };
        } catch(e) { return { notifications: [], unreadCount: 0 }; }
    },

    markNotificationsRead: async function() {
        if (!this.currentUser) return;
        try {
            await fetch('notifications.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'markRead', username: this.currentUser.username })
            });
        } catch(e) {}
    },

    initNotificationBell: function(bellId, badgeId, dropdownListId) {
        const refreshBell = async () => {
            const data = await this.fetchNotifications();
            const badge = document.getElementById(badgeId);
            const list = document.getElementById(dropdownListId);
            if (badge) {
                badge.textContent = data.unreadCount;
                badge.style.display = data.unreadCount > 0 ? 'inline-block' : 'none';
            }
            if (list) {
                if (!data.notifications || data.notifications.length === 0) {
                    list.innerHTML = '<li class="dropdown-item text-muted small">No notifications yet.</li>';
                } else {
                    list.innerHTML = data.notifications.slice(0, 10).map(n => `
                        <li>
                            <a class="dropdown-item small ${n.is_read ? '' : 'fw-bold'}" href="#"
                               ${n.link_report_id ? `onclick="viewDetails && viewDetails(${n.link_report_id})"` : ''}>
                                <i class="bi bi-bell-fill me-1 text-warning" style="font-size:0.7rem"></i>
                                ${n.message}
                                <div class="text-muted" style="font-size:0.65rem">${n.created_at}</div>
                            </a>
                        </li>`).join('');
                }
            }
        };
        const bell = document.getElementById(bellId);
        if (bell) {
            bell.addEventListener('click', async () => {
                await this.markNotificationsRead();
                setTimeout(refreshBell, 300);
            });
        }
        refreshBell();
        setInterval(refreshBell, 30000); // auto-refresh every 30s
    },

    toggleLeave: async function(username) {
        try {
            const res = await fetch('manage_users.php', {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggleLeave', username })
            });
            const data = await res.json();
            if (data.success) {
                const user = this.getData().users.find(u => u.username === username);
                if (user) user.isOnLeave = data.isOnLeave;
            }
            return data;
        } catch(e) { return { success: false, message: e.message }; }
    }
};

window.System = System;
System.init();

