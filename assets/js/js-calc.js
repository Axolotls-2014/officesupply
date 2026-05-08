// --------------------------- LOG VALUES UTILITY ---------------------------

function vals() {
  console.log("DISP: " + display.toString() + '\n CURR: ' + current.toString() + "\n MEMR: " + memory.toString() + "\n OPER: " + operation + "\n POST: " + postEqual);
}

// --------------------------- CLEAR/INIT ---------------------------

var display = "0",
    current = 0,
    memory = 0,
    operation = "none",
    postEqual = false,
    percent = false;

clearAll()

// --------------------------- DISPLAY ---------------------------

function removeLeadingZeros() {
  if (display.toString().charAt(0) == '0' && display.toString().charAt(1) !== ".") {
    display = display.toString().substr(1).toString();
  }
}

function updateDisplay() {
  if (current === NaN) {
    $('#display').text("ERROR");
  } else if (current > 9999999999999999 || current < -9999999999999999) {
    $('#display').text("ERROR");
  } else if (current < 0) {
    $('#displayLeft').text("-");
    $('#display').text((current * -1).toString().substring(0, 15));
  } else {
    $('#displayLeft').text("");
    $('#display').text(current.toString().substring(0, 16));
  }
  removeLeadingZeros();
  vals();
}

// --------------------------- FUNCTIONS ---------------------------

/*function roundTheNumber() {
  var wholeNums = 0;
  var decimalNums = 0;
  display = current.toFixed(1);
}*/

function sum(x) {
  operation = "sum";
  memory = current;
  current = 0;
}

function dif(x) {
  operation = "dif";
  memory = current;
  current = 0;
}

function mul(x) {
  operation = "mul";
  memory = current;
  current = 0;
}

function div(x) {
  operation = "div";
  memory = current;
  current = 0;
}

function prc(x) {
  if (operation === 'mul' || operation === 'div') {
    current *= 0.01;
  } else if (operation === 'sum' || operation === 'dif') {
    memory = current * 0.01 * memory;
  }
  updateDisplay();
}

function negate() {
  if (current === 0) {
    return;
  } else {
    current *= -1;
    updateDisplay();
  }
};

function equals(op) {
  if (op == "none") {
    return;
  } else {
    switch (op) {
      case "sum":
        current = memory + current;
             break;
      case "dif":
        current = memory - current;
             break;
      case "mul":
        current = memory * current;
             break;
      case "div":
        current = memory / current;
             break;
    }
     display = current;
    postEqual = true;

  };
  updateDisplay();
};

// --------------------------- INPUT FUNC ---------------------------

function concatDigit(val) {
  // Before operation, update Current and Display
  if (operation === "none") {
    display = display.toString() + val; // Concatenate value
    current = Number(display); // WYSIWYG
    updateDisplay();

    // During operation
  } else if (current === 0 || postEqual === true) {
    current = val;
    display = val.toString();
    postEqual = false; // Reset postEqual after first digit entered
    updateDisplay();

  } else {
    display = display.toString() + val; // Concatenate value
    current = Number(display); // WYSIWYG
    updateDisplay();
  }
}

function point() {
  //removeLeadingZeros();
  while (display.indexOf(".") !== -1) {
    return;
  }
  if (display == "0") {
    display = "0.";
    updateDisplay();
  } else {
    display = display.toString() + ".";
    updateDisplay();
  }
}

function clearAll() {
  display = "0";
  current = 0;
  memory = 0;
  operation = "none";
  updateDisplay();
}

function clearCurrent() {
  display = "0";
  current = 0;
  updateDisplay();
}

