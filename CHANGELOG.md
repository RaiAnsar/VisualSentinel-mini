# Changelog

All notable changes to the Visual Sentinel Mini project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

## [0.1.0] - 2024-04-14

### Added
- Password visibility toggle to login and registration pages
- Dark mode script in head section to prevent flash of incorrect theme during page load
- Dedicated passwordToggle.js module for handling password visibility toggling

### Changed
- Integrated password toggle through Vite instead of direct script inclusion
- Updated dark mode toggle in guest layout to use consistent class names (dark-icon, light-icon)

### Fixed
- Dark mode toggle in guest layout to match main app behavior
- Removed Alpine.js dark mode implementation from guest layout to fix conflicts
- Fixed jittery dark mode switching between light and dark themes

### Security
- Password visibility toggle preserves security while improving user experience 