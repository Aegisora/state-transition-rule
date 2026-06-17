# Aegisora State Transition Rule

![Code Coverage Badge](./badge.svg)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
![PHPStan Badge](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)

State Transition Rule provides a simple, rule-based state transition validation implementation for the Aegisora ecosystem.

It is built on top of aegisora/rule-contract (https://github.com/Aegisora/rule-contract) and follows its strict validation architecture, ensuring consistent and predictable behavior across applications.

This rule is useful for validating workflows, status changes, lifecycle transitions, and domain state machines.

---

## ✨ Features
- 🔹 Lightweight and dependency-free except aegisora/rule-contract
- 🔹 Validates transitions between named states
- 🔹 Supports explicit allowed transition maps
- 🔹 Supports array-based transition map creation
- 🔹 Ignores invalid raw map data safely
- 🔹 Deduplicates source states and transition states
- 🔹 Fully compatible with Aegisora validation pipeline
- 🔹 Strict `Context` → `Result` validation flow
- 🔹 No raw booleans — only structured results
- 🔹 Safe execution via base `Rule` abstraction
- 🔹 Simple factory API (create)
- 🔹 Ready to use out of the box

---

## 📦 Installation

```
composer require aegisora/state-transition-rule
```

---

## 🚀 Core Concept

This package implements a single validation rule:

- accepts a `StateTransition` value via `Context`
- checks whether transition `from` source state `to` target state is allowed
- returns a standardized `Result`

A transition is represented by two states:

`from → to`

Example:

`draft → paid`

The rule validates this transition against configured allowed transition maps.

---

## ⚖️ License

This package is open-source and licensed under the MIT License. See the LICENSE for details.

---

## 🌱 Contributing

Contributions are welcome and greatly appreciated!. See the CONTRIBUTING for details.

---

## 🌟 Support

If you find this project useful, please consider giving it a star on GitHub!

It helps the project grow and motivates further development.
