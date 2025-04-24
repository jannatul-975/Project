CREATE TABLE Teacher (TeacherID INT PRIMARY KEY, Name VARCHAR(100), Email VARCHAR(100), PhoneNo VARCHAR(15), DeptName VARCHAR(50), Designation VARCHAR(50));

CREATE TABLE Teacher_Application (ApplicationID INT PRIMARY KEY, Status VARCHAR(20), SubmissionDate TIMESTAMP, GuestInformation TEXT, CheckInDate DATE, CheckOutDate DATE, Purpose TEXT, TeacherID INT, FOREIGN KEY (TeacherID) REFERENCES Teacher(TeacherID));

CREATE TABLE Feedback (FeedbackID INT PRIMARY KEY, Rating INT, Comment TEXT, Date TIMESTAMP, BookingID INT, FOREIGN KEY (BookingID) REFERENCES Booking(BookingID));

CREATE TABLE Administrator (AdminID INT PRIMARY KEY, Name VARCHAR(100), Email VARCHAR(100), PhoneNo VARCHAR(15), Designation VARCHAR(50));

CREATE TABLE Register (RegisterID INT PRIMARY KEY, Name VARCHAR(100), Email VARCHAR(100), PhoneNo VARCHAR(15), Designation VARCHAR(50));

CREATE TABLE Guest (GuestID INT PRIMARY KEY, Name VARCHAR(100), Email VARCHAR(100), PhoneNo VARCHAR(15), Address TEXT, RegistrationDate DATE, Designation VARCHAR(50));

CREATE TABLE Room (RoomID INT PRIMARY KEY, RoomNo VARCHAR(10), RoomType VARCHAR(50), PricePerNight DECIMAL(10, 2), Status VARCHAR(20));

CREATE TABLE Booking (BookingID INT PRIMARY KEY, GuestID INT, RoomID INT, CheckInDate DATE, CheckOutDate DATE, Status VARCHAR(20), TotalAmount DECIMAL(10, 2), LastUpdate TIMESTAMP, FOREIGN KEY (GuestID) REFERENCES Guest(GuestID), FOREIGN KEY (RoomID) REFERENCES Room(RoomID));

CREATE TABLE Payment (PaymentID INT PRIMARY KEY, BookingID INT, PaymentDate DATE, PaidAmount DECIMAL(10, 2), PaymentMethod VARCHAR(50), PaymentStatus VARCHAR(20), FOREIGN KEY (BookingID) REFERENCES Booking(BookingID));

CREATE TABLE VerificationCode (CodeID INT PRIMARY KEY, Code VARCHAR(10), IsUsed BOOLEAN, CreatedAt TIMESTAMP);
