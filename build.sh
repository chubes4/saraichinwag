#!/bin/bash

# Sarai Chinwag Theme Build Script
# Creates optimized production package and versioned ZIP file

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Get theme version from style.css
VERSION=$(grep "^Version:" style.css | sed 's/Version: //' | tr -d ' ')

if [ -z "$VERSION" ]; then
    echo -e "${RED}Error: Could not extract version from style.css${NC}"
    exit 1
fi

echo -e "${BLUE}Building Sarai Chinwag Theme v${VERSION}${NC}"
echo "=================================="

# Clean and create dist directory
echo -e "${YELLOW}Setting up build directory...${NC}"
rm -rf dist
mkdir -p dist

# Copy all files except excluded ones
echo -e "${YELLOW}Copying theme files...${NC}"
rsync -av --progress \
    --exclude='dist/' \
    --exclude='docs/' \
    --exclude='.claude/' \
    --exclude='.git/' \
    --exclude='.DS_Store' \
    --exclude='*.log' \
    --exclude='build.sh' \
    --exclude='.vscode/' \
    --exclude='node_modules/' \
    --exclude='*.md' \
    ./ dist/saraichinwag/

# Create ZIP file from the saraichinwag directory contents
echo -e "${YELLOW}Creating ZIP file...${NC}"
cd dist/saraichinwag
zip -r "../saraichinwag.zip" . -x "*.DS_Store" "*.log"
cd ../..

# Display build summary
echo ""
echo -e "${GREEN}✓ Build completed successfully!${NC}"
echo ""
echo -e "${BLUE}Build Output:${NC}"
echo "  📁 dist/saraichinwag/ - Clean theme directory"
echo "  📦 dist/saraichinwag.zip - WordPress installation package"
echo ""
echo -e "${BLUE}Theme Details:${NC}"
echo "  Version: ${VERSION}"
echo "  Build Date: $(date)"
echo ""
echo -e "${GREEN}Ready for WordPress installation!${NC}"
echo -e "${YELLOW}Note: ZIP will update existing 'saraichinwag' theme when installed${NC}"